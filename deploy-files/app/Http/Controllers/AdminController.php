<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\ActivityLog;
use App\Models\ModeratedContent;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Analytics data
        $analytics = [
            'users' => [
                'total' => User::count(),
                'new_today' => User::whereDate('created_at', Carbon::today())->count(),
                'growth' => $this->calculateGrowth('users')
            ],
            'posts' => [
                'total' => Post::count(),
                'new_today' => Post::whereDate('created_at', Carbon::today())->count(),
                'growth' => $this->calculateGrowth('posts')
            ],
            'comments' => [
                'total' => Comment::count(),
                'new_today' => Comment::whereDate('created_at', Carbon::today())->count(),
                'growth' => $this->calculateGrowth('comments')
            ]
        ];

        // Get recent users for management section
        $users = User::latest()->take(5)->get();

        // Recent activity
        $recentActivity = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Content requiring moderation
        $pendingModeration = ModeratedContent::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.index', compact('analytics', 'users', 'recentActivity', 'pendingModeration'));
    }

    public function users()
    {
        $users = User::withCount(['posts', 'comments'])
            ->latest()
            ->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function userActivity($userId)
    {
        $user = User::findOrFail($userId);
        $activities = ActivityLog::where('user_id', $userId)
            ->latest()
            ->paginate(20);

        return view('admin.user-activity', compact('user', 'activities'));
    }

    public function moderation()
    {
        $pendingContent = ModeratedContent::with('moderator')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.moderation', compact('pendingContent'));
    }

    public function moderateContent(Request $request, $id)
    {
        $content = ModeratedContent::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'reason' => 'required_if:status,rejected|string|max:500',
            'action_taken' => 'required|string|max:255'
        ]);

        $content->update($validated);

        ActivityLogger::log(
            'content_moderation',
            "Moderated content ID: {$id} - Status: {$validated['status']}"
        );

        return back()->with('success', 'Content moderated successfully');
    }

    public function activityLogs()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.activity-logs', compact('logs'));
    }

    public function backup()
    {
        $backupPath = storage_path('app/backups');
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $backupPath . '/' . $filename
        );

        exec($command);

        ActivityLogger::log(
            'backup_created',
            "Database backup created: {$filename}"
        );

        return response()->download($backupPath . '/' . $filename)->deleteFileAfterSend();
    }

    protected function calculateGrowth($model)
    {
        $modelClass = match($model) {
            'users' => User::class,
            'posts' => Post::class,
            'comments' => Comment::class,
            default => throw new \InvalidArgumentException("Unknown model type: {$model}")
        };

        $today = $modelClass::whereDate('created_at', Carbon::today())->count();
        $yesterday = $modelClass::whereDate('created_at', Carbon::yesterday())->count();
        
        if ($yesterday == 0) return 100;
        
        return round((($today - $yesterday) / $yesterday) * 100, 2);
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id !== auth()->id()) {
            $wasAdmin = $user->is_admin;
            $user->is_admin = !$wasAdmin;
            $user->save();

            ActivityLogger::log(
                'toggle_admin',
                sprintf(
                    '%s user %s as admin',
                    $wasAdmin ? 'Removed' : 'Set',
                    $user->name
                )
            );

            return back()->with('success', 'User admin status updated successfully!');
        }
        return back()->with('error', 'You cannot modify your own admin status.');
    }

    public function comments()
    {
        $comments = Comment::with(['user', 'post'])
            ->latest()
            ->paginate(10);
        return view('admin.comments', compact('comments'));
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully!');
    }
}
