

<?php $__env->startSection('page-content'); ?>
<style>
    :root{
        --brand:#2563eb;
        --brand-600:#1d4ed8;
        --ink:#111827;
        --muted:#6b7280;
        --line:#e5e7eb;
        --bg:#f8fafc;
        --card:#ffffff;
    }

    .wrap{ max-width: 980px; margin:0 auto; padding: 10px; }
    .header{
        background: var(--card);
        border:1px solid var(--line);
        border-radius:16px;
        padding:16px;
        display:flex; align-items:center; justify-content:space-between; gap:12px;
        box-shadow: 0 1px 2px rgba(0,0,0,.06);
        margin-bottom: 12px;
    }
    .title h2{ margin:0; font-size:24px; font-weight:900; color:var(--ink); }
    .title p{ margin:4px 0 0; color:var(--muted); }

    .btn-brand{
        background: var(--brand);
        color:#fff;
        border:none;
        border-radius:12px;
        padding:10px 14px;
        font-weight:900;
        cursor:pointer;
        display:inline-flex; align-items:center; gap:8px;
    }
    .btn-brand:hover{ background: var(--brand-600); }

    .card{
        background: var(--card);
        border:1px solid var(--line);
        border-radius:16px;
        padding:16px;
        box-shadow: 0 1px 2px rgba(0,0,0,.06);
        margin-bottom: 12px;
    }

    .row{ display:flex; align-items:center; gap:12px; }
    .space{ display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
    .avatar{
        width:44px; height:44px; border-radius:999px;
        display:flex; align-items:center; justify-content:center;
        background:#dbeafe; color:#1d4ed8; font-weight:900;
        flex:0 0 auto;
    }
    .name{ margin:0; font-weight:900; color:var(--ink); }
    .sub{ margin:2px 0 0; font-size:12px; color:var(--muted); }

    .pill{
        display:inline-flex; align-items:center; gap:6px;
        font-size:12px;
        padding:4px 10px;
        border-radius:999px;
        border:1px solid var(--line);
        background:#f9fafb;
        color:#374151;
        white-space:nowrap;
    }

    .post-title{ font-weight:950; font-size:16px; color:var(--ink); margin-top:12px; }
    .post-body{
        margin-top:6px;
        color:#374151;
        white-space:pre-line;
        word-break:break-word;
        overflow-wrap:anywhere;
        line-height:1.6;
    }


    .divider{ border-top:1px solid #eef2f7; margin: 12px 0; }

    /* Modal */
    .modal-backdrop-custom{
        position:fixed; inset:0; background:rgba(17,24,39,.45);
        display:none; align-items:center; justify-content:center;
        z-index:1050; padding:16px;
    }
    .modal-custom{
        width:min(820px, 100%);
        background:var(--card);
        border:1px solid var(--line);
        border-radius:18px;
        box-shadow: 0 16px 40px rgba(0,0,0,.25);
        overflow:hidden;
    }
    .modal-head{
        padding:14px 16px;
        display:flex; align-items:center; justify-content:space-between; gap:10px;
        border-bottom:1px solid #eef2f7;
    }
    .modal-head h3{ margin:0; font-size:16px; font-weight:950; color:var(--ink); }
    .modal-close{
        border:none; background:#f3f4f6; color:#111827;
        border-radius:10px; padding:8px 10px; cursor:pointer; font-weight:900;
    }
    .modal-body{ padding:16px; }
    .input, .select, .textarea{
        width:100%;
        border:1px solid var(--line);
        border-radius:12px;
        padding:10px 12px;
        outline:none;
        background:#fff;
    }
    .textarea{ min-height:120px; resize:vertical; }

    .grid{ display:grid; grid-template-columns: 1fr 1fr; gap:10px; }
    @media(max-width:768px){ .grid{ grid-template-columns:1fr; } }

    .aud{
        display:grid; grid-template-columns: 1fr 1fr;
        gap:10px; margin-top:10px;
    }
    @media(max-width:768px){ .aud{ grid-template-columns:1fr; } }

    .aud-item{
        border:1px solid var(--line);
        border-radius:14px;
        padding:12px;
        cursor:pointer;
        display:flex; gap:10px; align-items:flex-start;
        background:#fff;
    }
    .aud-item:hover{ background:#f9fafb; }
    .aud-item input{ margin-top:3px; }
    .aud-item b{ display:block; color:var(--ink); }
    .aud-item small{ color:var(--muted); }

    .pick-box{
        margin-top:12px;
        border:1px solid var(--line);
        border-radius:14px;
        padding:12px;
        background:#f9fafb;
        display:none;
    }
    .pick-top{
        display:flex; justify-content:space-between; align-items:center; gap:10px;
        margin-bottom:10px;
    }
    .chips{ display:flex; flex-wrap:wrap; gap:8px; }
    .chip{
        background:#fff;
        border:1px solid var(--line);
        border-radius:999px;
        padding:6px 10px;
        font-size:12px;
        display:inline-flex; align-items:center; gap:8px;
    }
    .chip button{
        border:none; background:#f3f4f6;
        border-radius:999px; padding:2px 8px;
        cursor:pointer; font-weight:900;
    }

    .results{ max-height:240px; overflow:auto; }
    .res{
        background:#fff; border:1px solid var(--line);
        border-radius:14px; padding:10px;
        display:flex; justify-content:space-between; gap:10px;
        margin-bottom:8px;
        cursor:pointer;
    }
    .res:hover{ background:#f3f4f6; }
    .res b{ color:var(--ink); }
    .res small{ color:var(--muted); }

    .footer-actions{ margin-top:12px; display:flex; justify-content:flex-end; gap:10px; }
    .btn-ghost{
        border:1px solid var(--line);
        background:#fff;
        color:#111827;
        border-radius:12px;
        padding:10px 14px;
        font-weight:900;
        cursor:pointer;
    }
</style>

<div class="wrap">
    <div class="header">
        <div class="title">
            <h2>Announcements</h2>
            <p>Post updates and notify students (system + email).</p>
        </div>
        <button class="btn-brand" id="openModalBtn">➕ Create Announcement</button>
    </div>

    <?php if(session('success')): ?>
        <div class="card" style="border-color:#bbf7d0;background:#f0fdf4;color:#166534;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card">
            <div class="space">
                <div class="row">
                    <div class="avatar" style="background:#f3f4f6;color:#374151;">
                        <?php echo e(strtoupper(substr($post->creator->firstname ?? 'C', 0, 1))); ?>

                    </div>
                    <div>
                        <p class="name"><?php echo e($post->creator->firstname ?? 'Coordinator'); ?> <?php echo e($post->creator->lastname ?? ''); ?></p>
                        <p class="sub">
                           <span class="js-timeago"
                                data-time="<?php echo e($post->posted_at?->toIso8601String()); ?>"
                                title="<?php echo e($post->posted_at?->format('M d, Y h:i A')); ?>"
                                <?php echo e($post->posted_at?->diffForHumans()); ?>>
                            </span>

                            <span class="pill">
                                <?php echo e(match($post->audience){
                                    'all_students' => 'All Students',
                                    'all_scholars' => 'All Scholars',
                                    'specific_students' => 'Specific Students',
                                    'specific_scholars' => 'Specific Scholars',
                                    default => 'Audience'
                                }); ?>

                            </span>
                            •
                            <span class="pill" title="Views">
                                👁️ <?php echo e($post->views_count ?? 0); ?>

                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="post-title"><?php echo e($post->title); ?></div>
            <div class="post-body"><?php echo e($post->description); ?></div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card" style="text-align:center; color:#6b7280;">
            No announcements yet.
        </div>
    <?php endif; ?>

    <div style="margin-top:14px;">
        <?php echo e($announcements->links()); ?>

    </div>
</div>


<div class="modal-backdrop-custom" id="createModal">
    <div class="modal-custom">
        <div class="modal-head">
            <h3>Create Announcement</h3>
            <button class="modal-close" id="closeModalBtn">✕</button>
        </div>

        <form class="modal-body" action="<?php echo e(route('coordinator.announcements.store')); ?>" method="POST" id="announceForm">
            <?php echo csrf_field(); ?>

            <div class="grid">
                <div>
                    <label class="sub" style="font-weight:900;">Title</label>
                    <input class="input" name="title" placeholder="Announcement title…" required>
                </div>
                <div>
                    <label class="sub" style="font-weight:900;">Posting Time</label>
                    <input class="input" value="<?php echo e(now()->format('M d, Y h:i A')); ?> (auto)" disabled>
                </div>
            </div>

            <div style="margin-top:10px;">
                <label class="sub" style="font-weight:900;">Description</label>
                <textarea class="textarea" name="description" placeholder="What do you want to announce?" required></textarea>
            </div>

            <div style="margin-top:10px;">
                <label class="sub" style="font-weight:900;">Audience</label>
                <div class="aud" id="audienceCards">
                    <label class="aud-item">
                        <input type="radio" name="audience" value="all_students" checked>
                        <div><b>All Students</b><small>Notify all student accounts</small></div>
                    </label>
                    <label class="aud-item">
                        <input type="radio" name="audience" value="all_scholars">
                        <div><b>All Scholars</b><small>Notify all scholars only</small></div>
                    </label>
                    <label class="aud-item">
                        <input type="radio" name="audience" value="specific_students">
                        <div><b>Specific Students</b><small>Pick students (search, no preload)</small></div>
                    </label>
                    <label class="aud-item">
                        <input type="radio" name="audience" value="specific_scholars">
                        <div><b>Specific Scholars</b><small>Pick scholars (search, no preload)</small></div>
                    </label>
                </div>
            </div>

            
            <div class="pick-box" id="pickerBox">
                <div class="pick-top">
                    <div>
                        <b style="color:var(--ink);">Select recipients</b><br>
                        <small class="sub">Type a name / student id / email to search</small>
                    </div>
                    <span class="pill" id="pickedCount">0 selected</span>
                </div>

                <input class="input" id="searchInput" placeholder="Search…" autocomplete="off">

                <div class="divider"></div>

                <div class="chips" id="chips"></div>

                <div class="divider"></div>

                <div class="results" id="results"></div>
            </div>

            <div class="footer-actions">
                <button type="button" class="btn-ghost" id="cancelBtn">Cancel</button>
                <button type="submit" class="btn-brand">Post & Notify</button>
            </div>
        </form>
    </div>
</div>

<script>
(function(){
    const modal = document.getElementById('createModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    const pickerBox = document.getElementById('pickerBox');
    const searchInput = document.getElementById('searchInput');
    const results = document.getElementById('results');
    const chips = document.getElementById('chips');
    const pickedCount = document.getElementById('pickedCount');

    // store selected
    let selected = new Map(); // key -> object
    let currentType = null;   // students | scholars
    let debounce = null;

    function openModal(){
        const form = document.getElementById('announceForm');
        form.reset();

        selected.clear();
        updatePickedUI();
        setPickerVisibility();

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

   function closeModal(){
        modal.style.display = 'none';
        document.body.style.overflow = '';

        // ✅ reset normal fields
        const form = document.getElementById('announceForm');
        form.reset();

        // ✅ reset picker area
        results.innerHTML = '';
        searchInput.value = '';
        selected.clear();
        updatePickedUI();
        setPickerVisibility(); // hides picker if not specific
    }

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e)=>{ if(e.target === modal) closeModal(); });

    function updatePickedUI(){
        chips.innerHTML = '';
        selected.forEach((v, k) => {
            const el = document.createElement('span');
            el.className = 'chip';
            el.innerHTML = `
                ${v.label}
                <button type="button" aria-label="remove">×</button>
            `;
            el.querySelector('button').addEventListener('click', ()=>{
                selected.delete(k);
                updatePickedUI();
            });
            chips.appendChild(el);
        });
        pickedCount.textContent = `${selected.size} selected`;

        // remove old hidden inputs
        document.querySelectorAll('.dyn-picked').forEach(x => x.remove());

        // add hidden inputs (based on current audience)
        const aud = document.querySelector('input[name="audience"]:checked')?.value;
        const form = document.getElementById('announceForm');

        if (aud === 'specific_students') {
            selected.forEach((v) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_users[]';
                input.value = v.id;
                input.className = 'dyn-picked';
                form.appendChild(input);
            });
        }

        if (aud === 'specific_scholars') {
            selected.forEach((v) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_scholars[]';
                input.value = v.id;
                input.className = 'dyn-picked';
                form.appendChild(input);
            });
        }
    }

    function setPickerVisibility(){
        const aud = document.querySelector('input[name="audience"]:checked')?.value;
        const needsPicker = (aud === 'specific_students' || aud === 'specific_scholars');
        pickerBox.style.display = needsPicker ? 'block' : 'none';

        // reset selection when switching type
        if (needsPicker) {
            currentType = (aud === 'specific_students') ? 'students' : 'scholars';
        } else {
            currentType = null;
            selected.clear();
            updatePickedUI();
        }
    }

    document.querySelectorAll('input[name="audience"]').forEach(r=>{
        r.addEventListener('change', ()=>{
            selected.clear();
            updatePickedUI();
            setPickerVisibility();
        });
    });
    setPickerVisibility();

    async function fetchRecipients(q){
        if(!currentType) return [];
        const url = new URL("<?php echo e(route('coordinator.announcements.recipients')); ?>", window.location.origin);
        url.searchParams.set('type', currentType);
        url.searchParams.set('q', q);

        const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
        return await res.json();
    }

    function renderResults(list){
        results.innerHTML = '';
        if(!list.length){
            results.innerHTML = `<div class="sub">No results.</div>`;
            return;
        }

        list.forEach(item=>{
            // students: id is user id
            // scholars: id is scholar id
            const id = item.id;

            const fullName = `${item.firstname ?? ''} ${item.lastname ?? ''}`.trim();
            const meta = `${item.student_id ?? ''}${item.bisu_email ? ' • ' + item.bisu_email : ''}`.trim();

            const key = currentType + ':' + id;

            const card = document.createElement('div');
            card.className = 'res';
            card.innerHTML = `
                <div>
                    <b>${fullName || 'Unnamed'}</b><br>
                    <small>${meta || ''}</small>
                </div>
                <div class="pill">${selected.has(key) ? 'Selected' : 'Select'}</div>
            `;

            card.addEventListener('click', ()=>{
                if(selected.has(key)){
                    selected.delete(key);
                } else {
                    selected.set(key, { id, label: fullName });
                }
                updatePickedUI();
                renderResults(list);
            });

            results.appendChild(card);
        });
    }

     function timeAgoText(date){
        const now = new Date();
        const then = new Date(date);
        if (isNaN(then.getTime())) return ''; // invalid date, do nothing

        const diff = Math.floor((now - then) / 1000); // seconds

        if (diff < 5) return 'just now';
        if (diff < 60) return `${diff}s ago`;

        const mins = Math.floor(diff / 60);
        if (mins < 60) return `${mins}m ago`;

        const hrs = Math.floor(mins / 60);
        if (hrs < 24) return `${hrs}h ago`;

        const days = Math.floor(hrs / 24);
        if (days === 1) return 'yesterday';
        if (days < 7) return `${days}d ago`;

        // fallback: show actual date like Feb 1, 2026
        return then.toLocaleDateString(undefined, { year:'numeric', month:'short', day:'numeric' });
    }

    function refreshTimeago(){
        document.querySelectorAll('.js-timeago').forEach(el=>{
            const t = el.getAttribute('data-time');
            if(!t) return;
            const txt = timeAgoText(t);
            if(txt) el.textContent = txt;
        });
    }

    // run now + repeat
    refreshTimeago();
    setInterval(refreshTimeago, 30000);


    searchInput.addEventListener('input', ()=>{
        clearTimeout(debounce);
        debounce = setTimeout(async ()=>{
            const q = searchInput.value.trim();
            if(q.length < 2){
                results.innerHTML = `<div class="sub">Type at least 2 characters…</div>`;
                return;
            }
            results.innerHTML = `<div class="sub">Searching…</div>`;
            const data = await fetchRecipients(q);
            renderResults(data);
        }, 250);
    });

    // basic guard: if specific audience, must pick at least 1
    document.getElementById('announceForm').addEventListener('submit', (e)=>{
        const aud = document.querySelector('input[name="audience"]:checked')?.value;
        if ((aud === 'specific_students' || aud === 'specific_scholars') && selected.size === 0) {
            e.preventDefault();
            alert('Please select at least 1 recipient.');
        }
    });


   
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.coordinator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\scholarship-information\resources\views/coordinator/manage-announcements.blade.php ENDPATH**/ ?>