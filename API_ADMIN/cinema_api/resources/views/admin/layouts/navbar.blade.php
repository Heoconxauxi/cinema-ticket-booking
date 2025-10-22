<nav class="navbar navbar-main shadow-sm mb-3 navbar-main px-4 py-2 sticky-top">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="flex-shrink-0">
            <div id="clock" class="fw-semibold text-white px-3 py-1 rounded shadow-sm small-clock"></div>
        </div>

        <div class="flex-grow-1 text-center">
            <span class="text-muted">Xin chào,</span>
            <span class="fw-bold text-primary">{{ Auth::user()->TenND ?? 'Admin' }}</span>
        </div>

        <div class="flex-shrink-0">
            <div id="avatar-container">
                <img id="avatar" src="{{ asset('assets/img/default-avatar.png') }}" alt="Avatar">
            </div>
        </div>
    </div>
</nav>

<style>
    .navbar-main {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-top: 1rem;
        position: sticky;
        top: 1rem;
        z-index: 100;
        border: 1px solid #000;
    }

    /* Đồng hồ */
    #clock {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-radius: 6px;
        font-size: 0.9rem;
        min-width: 85px;
        text-align: center;
        box-shadow: 0 0 6px rgba(37, 99, 235, 0.4);
        color: #fff;
    }

    /* Avatar */
    #avatar-container {
        width: 38px;
        height: 38px;
        overflow: hidden;
        border-radius: 50%;
        border: 2px solid #2563eb;
        box-shadow: 0 0 5px rgba(59,130,246,0.3);
    }
    #avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        const clockElement = document.getElementById('clock');
        if (clockElement) {
             clockElement.textContent = `${h}:${m}:${s}`;
        }
    }
    updateClock();
    setInterval(updateClock, 1000);
});
</script>