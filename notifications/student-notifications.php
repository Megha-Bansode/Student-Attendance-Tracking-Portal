<?php
/**
 * AttendEase - Student Notifications Feed
 * Dynamically loads notifications and persists state (read/unread/deleted) via PHP Sessions
 */
$page_title  = 'Notifications';
include '../includes/header.php';

// Initialize session to store read and deleted notification IDs
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['read_notifs'])) {
    $_SESSION['read_notifs'] = [];
}
if (!isset($_SESSION['deleted_notifs'])) {
    $_SESSION['deleted_notifs'] = [];
}

// Handle AJAX actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $notif_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($_POST['action'] === 'mark_read') {
        if ($notif_id && !in_array($notif_id, $_SESSION['read_notifs'])) {
            $_SESSION['read_notifs'][] = $notif_id;
        }
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($_POST['action'] === 'mark_all_read') {
        $file_path = __DIR__ . '/../config/notifications.json';
        if (file_exists($file_path)) {
            $all_notifs = json_decode(file_get_contents($file_path), true) ?: [];
            foreach ($all_notifs as $n) {
                $id = intval($n['id']);
                if (!in_array($id, $_SESSION['read_notifs'])) {
                    $_SESSION['read_notifs'][] = $id;
                }
            }
        }
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($_POST['action'] === 'delete') {
        if ($notif_id && !in_array($notif_id, $_SESSION['deleted_notifs'])) {
            $_SESSION['deleted_notifs'][] = $notif_id;
        }
        echo json_encode(['success' => true]);
        exit;
    }
}

// Load notifications from config/notifications.json
$file_path = __DIR__ . '/../config/notifications.json';
$all_notifications = [];
if (file_exists($file_path)) {
    $all_notifications = json_decode(file_get_contents($file_path), true) ?: [];
}

$notifications = [];
foreach ($all_notifications as $n) {
    $id = intval($n['id']);
    // Skip deleted notifications
    if (in_array($id, $_SESSION['deleted_notifs'])) {
        continue;
    }
    
    // Set unread override
    if (in_array($id, $_SESSION['read_notifs'])) {
        $n['unread'] = false;
    }
    
    // Calculate relative time
    $n['time'] = get_relative_time_student($n['created_at']);
    $notifications[] = $n;
}

// Show newest notifications first
$notifications = array_reverse($notifications);

function get_relative_time_student($datetime) {
    $time = strtotime($datetime);
    if (!$time) return 'some time ago';
    $diff = time() - $time;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return round($diff / 60) . ' mins ago';
    if ($diff < 86400) return round($diff / 3600) . ' hours ago';
    return round($diff / 86400) . ' days ago';
}
?>
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/dashboard.css">
<script>
document.body.classList.add('faculty-portal-body');
if (window.innerWidth >= 992 && localStorage.getItem('facultySidebarCollapsed') === 'true') {
    document.documentElement.classList.add('preload-collapsed');
}
</script>

<div class="faculty-portal">
    <div class="faculty-portal-wrapper">

        <!-- Sidebar -->
        <?php include '../includes/student-sidebar.php'; ?>

        <!-- Main content -->
        <div class="faculty-main-content">

            <!-- Page Title Band -->
            <div class="faculty-page-band">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="faculty-page-band-title">
                            <i class="bi bi-bell"></i> Notifications
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Student / Alerts Feed</p>
                    </div>
                </div>
                <div class="faculty-page-band-right">
                    <div class="topbar-date-pill">
                        <i class="bi bi-calendar3"></i>
                        <span><?php echo date('D, M d, Y'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Alert feed list -->
                <div class="faculty-card">
                    <div class="faculty-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-envelope-paper-fill" style="color: #f59e0b;"></i> Inbox Messages</h3>
                            <p class="faculty-card-subtitle">Showing all system updates and attendance warnings</p>
                        </div>
                        <button class="btn btn-sm btn-outline-light rounded-pill px-3" id="btnMarkAllRead" style="font-size: 0.8rem;"><i class="bi bi-check2-all me-1"></i> Mark all as read</button>
                    </div>
                    
                    <div class="p-3">
                        <div class="d-flex flex-column gap-3" id="notificationList">
                            <?php if (empty($notifications)): ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-chat-left-dots text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-light-subtitle mt-3 mb-0">You have no new notifications.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($notifications as $n): ?>
                                    <div class="card p-3 notification-item-card <?php echo $n['unread'] ? 'unread-glow' : ''; ?>" 
                                         data-id="<?php echo $n['id']; ?>"
                                         style="background: <?php echo $n['bg_glow']; ?>; border: 1px solid <?php echo $n['unread'] ? 'rgba(245, 158, 11, 0.25)' : 'rgba(255, 255, 255, 0.05)'; ?>; border-radius: 12px; transition: all 0.3s ease;">
                                        <div class="d-flex gap-3 align-items-start">
                                            <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; background: rgba(255,255,255,0.03); color: <?php echo $n['icon_color']; ?>; border: 1px solid rgba(255,255,255,0.08);">
                                                <i class="bi <?php echo $n['icon']; ?>" style="font-size: 1.2rem;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                                    <h4 class="h6 fw-bold mb-1 <?php echo $n['unread'] ? 'text-white' : 'text-light-subtitle'; ?>" style="font-family: 'Outfit', sans-serif;">
                                                        <?php echo htmlspecialchars($n['title']); ?>
                                                        <?php if ($n['unread']): ?>
                                                            <span class="badge rounded-pill bg-amber-subtle text-amber ms-2" style="font-size: 0.65rem; background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2);">NEW</span>
                                                        <?php endif; ?>
                                                    </h4>
                                                    <small style="color: #64748b; font-size: 0.75rem;"><?php echo $n['time']; ?></small>
                                                </div>
                                                <p class="mb-0 text-light-subtitle mt-1" style="font-size: 0.85rem; color: #94a3b8;"><?php echo htmlspecialchars($n['message']); ?></p>
                                            </div>
                                            <div class="flex-shrink-0 d-flex gap-1">
                                                <?php if ($n['unread']): ?>
                                                    <button class="btn btn-sm btn-outline-light py-1 px-2 btn-mark-read" style="font-size: 0.72rem; border-color: rgba(255,255,255,0.15);"><i class="bi bi-check-lg"></i></button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-outline-danger py-1 px-2 btn-delete-notif" style="font-size: 0.72rem; border-color: rgba(239, 68, 68, 0.15);"><i class="bi bi-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notifContainer = document.getElementById('notificationList');
    const markAllBtn = document.getElementById('btnMarkAllRead');

    if (notifContainer) {
        // Attach click events on elements
        notifContainer.addEventListener('click', function(e) {
            // Handle Mark Read
            const markBtn = e.target.closest('.btn-mark-read');
            if (markBtn) {
                const card = markBtn.closest('.notification-item-card');
                const id = card.getAttribute('data-id');
                
                const formData = new FormData();
                formData.append('action', 'mark_read');
                formData.append('id', id);

                fetch('student-notifications.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        card.classList.remove('unread-glow');
                        card.style.background = 'rgba(255,255,255,0.01)';
                        card.style.borderColor = 'rgba(255,255,255,0.05)';
                        const badge = card.querySelector('.badge');
                        if (badge) badge.remove();
                        markBtn.remove();
                        if (window.showFacultyToast) {
                            showFacultyToast('Notification marked as read.', 'info');
                        }
                    }
                });
                return;
            }

            // Handle Delete Notif
            const deleteBtn = e.target.closest('.btn-delete-notif');
            if (deleteBtn) {
                const card = deleteBtn.closest('.notification-item-card');
                const id = card.getAttribute('data-id');

                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('student-notifications.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            card.remove();
                            if (window.showFacultyToast) {
                                showFacultyToast('Notification deleted successfully.', 'warning');
                            }
                            // If list is now empty, reload to show the "no notifications" template
                            if (notifContainer.children.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                });
                return;
            }
        });
    }

    if (markAllBtn) {
        // Mark all as read
        markAllBtn.addEventListener('click', function() {
            const formData = new FormData();
            formData.append('action', 'mark_all_read');

            fetch('student-notifications.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const unreadCards = notifContainer.querySelectorAll('.unread-glow');
                    unreadCards.forEach(card => {
                        card.classList.remove('unread-glow');
                        card.style.background = 'rgba(255,255,255,0.01)';
                        card.style.borderColor = 'rgba(255,255,255,0.05)';
                        const badge = card.querySelector('.badge');
                        if (badge) badge.remove();
                        const markBtn = card.querySelector('.btn-mark-read');
                        if (markBtn) markBtn.remove();
                    });
                    if (window.showFacultyToast) {
                        showFacultyToast('All messages marked as read.', 'success');
                    }
                }
            });
        });
    }
});
</script>
<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../includes/footer.php'; ?>
