@extends('layouts.coordinator')

@section('page-content')
<style>
    :root{
        --brand:#0b2e5e;
        --brand-600:#123f85;
        --brand-700:#0a2550;
        --brand-soft:#eaf2ff;
        --brand-line:#bcd6ff;
        --ink:#1e293b;
        --muted:#6b7280;
        --line:#e5e7eb;
        --bg:#f2f7ff;
        --card:#ffffff;
        --danger:#b91c1c;
        --danger-bg:#fff1f2;
        --danger-line:#fecdd3;
    }

    .wrap{max-width:1040px;margin:0 auto;padding:16px;color:var(--ink);}
    .page-shell{
        background:
            radial-gradient(1100px 380px at 10% 0%, rgba(11,46,94,.14) 0%, rgba(11,46,94,0) 55%),
            linear-gradient(180deg, var(--bg), #ffffff);
        border-radius:22px;
        padding:12px;
        border:1px solid rgba(188,214,255,.65);
    }
    .header{
        max-width:920px;
        margin:0 auto 14px;
        background:linear-gradient(135deg, var(--brand), var(--brand-600));
        border-radius:22px;
        padding:18px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
        color:#fff;
        box-shadow:0 .75rem 2rem rgba(2,6,23,.16);
    }
    .title h2{margin:0;font-size:1.45rem;font-weight:600;color:#fff;}
    .title p{margin:6px 0 0;color:rgba(255,255,255,.85);font-size:.92rem;}

    .card{
        background:#fff;
        border:1px solid var(--line);
        border-radius:20px;
        padding:18px;
        box-shadow:0 .45rem 1.25rem rgba(15,23,42,.06);
        margin:0 auto 14px;
        width:100%;
        max-width:920px;
        position:relative;
        overflow:hidden;
    }
    .card::before{
        content:"";
        position:absolute;
        left:0; top:0; right:0;
        height:4px;
        background:linear-gradient(90deg, var(--brand), var(--brand-600));
    }
    .rowx{display:flex;align-items:flex-start;gap:12px;}
    .space{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;}
    .avatar{
        width:46px;height:46px;border-radius:999px;
        display:flex;align-items:center;justify-content:center;
        background:var(--brand-soft);
        border:1px solid var(--brand-line);
        color:var(--brand);
        font-weight:600;
        flex:0 0 auto;
    }
    .name{margin:0;font-weight:500;font-size:.95rem;color:var(--brand-700);}
    .sub{
        margin:4px 0 0;
        font-size:.82rem;
        color:var(--muted);
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        align-items:center;
    }
    .pill{
        display:inline-flex;align-items:center;gap:6px;
        font-size:.72rem;
        font-weight:500;
        padding:4px 10px;
        border-radius:999px;
        border:1px solid var(--line);
        background:#f8fafc;
        color:#334155;
        white-space:nowrap;
    }
    .pill-blue{
        background:var(--brand-soft);
        border-color:var(--brand-line);
        color:var(--brand);
    }

    .post-title{
        font-weight:600;
        font-size:1rem;
        color:var(--brand-700);
        margin-top:10px;
    }
    .post-body{
        margin-top:8px;
        color:#334155;
        white-space:pre-line;
        word-break:break-word;
        overflow-wrap:anywhere;
        line-height:1.65;
        font-size:.92rem;
    }
    .post-image{
        margin-top:12px;
        width:100%;
        max-height:420px;
        object-fit:cover;
        border-radius:16px;
        border:1px solid #e5e7eb;
    }

    .btnx{
        border:1px solid transparent;
        border-radius:12px;
        padding:9px 12px;
        font-weight:500;
        font-size:.88rem;
        display:inline-flex;
        align-items:center;
        gap:8px;
        cursor:pointer;
        text-decoration:none;
        line-height:1;
        white-space:nowrap;
    }
    .btnx-primary{
        background:linear-gradient(180deg, var(--brand), var(--brand-600));
        border-color:rgba(18,63,133,.50);
        color:#fff;
    }
    .btnx-secondary{
        background:#fff;
        border-color:var(--line);
        color:var(--ink);
    }
    .btnx-danger{
        background:var(--danger-bg);
        border-color:var(--danger-line);
        color:var(--danger);
    }
    .btnx-sm{padding:7px 10px;font-size:12px;}

    .pagination-wrap{
        max-width:920px;
        margin:12px auto 0;
        border:1px solid rgba(188,214,255,.85);
        border-radius:18px;
        background:#fff;
        padding:10px 12px;
    }

    .modal-backdrop-custom{
        position:fixed; inset:0; background:rgba(17,24,39,.45);
        display:none; align-items:center; justify-content:center;
        z-index:1050; padding:16px;
    }
    .modal-custom{
        width:min(900px, 100%);
        max-height:90vh;
        background:var(--card);
        border:1px solid var(--line);
        border-radius:20px;
        box-shadow:0 18px 55px rgba(0,0,0,.22);
        overflow:hidden;
        display:flex;
        flex-direction:column;
    }
    .modal-head{
        padding:14px 16px;
        display:flex; align-items:center; justify-content:space-between;
        border-bottom:1px solid #eef2f7;
        background:linear-gradient(180deg, #ffffff, #f8fbff);
    }
    .modal-head h3{margin:0;font-size:.98rem;font-weight:600;color:var(--brand-700);}
    .modal-close{
        border:none;background:#f1f5f9;color:#0f172a;
        border-radius:12px;padding:8px 10px;cursor:pointer;
    }
    .modal-body{padding:16px;overflow:auto;flex:1 1 auto;}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
    @media(max-width:768px){ .grid{grid-template-columns:1fr;} }

    .input,.select,.textarea{
        width:100%;
        border:1px solid var(--line);
        border-radius:14px;
        padding:10px 12px;
        outline:none;
        background:#fff;
        font-size:.92rem;
    }
    .textarea{min-height:120px;resize:vertical;}
    .section-box{
        margin-top:12px;
        border:1px solid var(--line);
        border-radius:16px;
        padding:14px;
        background:#f8fafc;
    }
    .section-title{
        font-weight:600;
        color:var(--brand-700);
        margin-bottom:10px;
    }

    .results{max-height:260px;overflow:auto;margin-top:10px;}
    .res{
        background:#fff;border:1px solid var(--line);
        border-radius:14px;padding:10px 12px;
        display:flex;justify-content:space-between;gap:10px;
        margin-bottom:8px;cursor:pointer;
    }
    .res:hover{background:var(--brand-soft);border-color:var(--brand-line);}
    .res b{font-weight:500;color:var(--brand-700);}
    .res small{color:var(--muted);}

    .chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px;}
    .chip{
        background:#fff;border:1px solid var(--line);
        border-radius:999px;padding:6px 10px;font-size:12px;
        display:inline-flex;align-items:center;gap:8px;
    }
    .chip button{
        border:none;background:#f1f5f9;border-radius:999px;
        padding:2px 8px;cursor:pointer;
    }

    .footer-actions{
        margin-top:14px;
        display:flex;
        justify-content:flex-end;
        gap:10px;
        position:sticky;
        bottom:0;
        background:#fff;
        padding-top:12px;
        border-top:1px solid #eef2f7;
    }

    .helper{
        font-size:.83rem;
        color:var(--muted);
        margin-top:6px;
    }

    .hidden{display:none !important;}

        .compact-grid{
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap:10px;
    }
    @media(max-width:768px){
        .compact-grid{ grid-template-columns:1fr; }
    }

    .compact-select{
        min-height: 42px;
    }

    .selection-summary{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin-top:10px;
    }

    .summary-pill{
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:6px 12px;
        border-radius:999px;
        background:var(--brand-soft);
        border:1px solid var(--brand-line);
        color:var(--brand-700);
        font-size:.82rem;
        font-weight:600;
    }

    .compact-recipient-box{
        padding:12px;
    }

    .compact-recipient-box .results{
        max-height:180px;
        overflow:auto;
        margin-top:8px;
    }

    .compact-recipient-box .chips{
        margin-top:8px;
        max-height:72px;
        overflow:auto;
    }

    .announcement-focus{
        border:1.5px solid var(--brand-line);
        background:#fff;
        box-shadow:0 10px 24px rgba(11,46,94,.06);
    }

    .announcement-focus .section-title{
        font-size:1rem;
        color:var(--brand);
    }

    .title-strong{
        font-size:1rem;
        font-weight:600;
        color:var(--brand-700);
        margin-bottom:6px;
        display:block;
    }

    .input-title{
        min-height:48px;
        font-size:1rem;
        font-weight:500;
    }

    .textarea-announce{
        min-height:180px;
        font-size:.98rem;
        line-height:1.6;
    }

    .setup-muted{
        background:#f8fafc;
    }

    .recipient-toggle{
    width:100%;
    border:1px solid var(--line);
    background:#fff;
    border-radius:14px;
    padding:12px 14px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    cursor:pointer;
    transition:.15s ease;
}
.recipient-toggle:hover{
    border-color:var(--brand-line);
    background:var(--brand-soft);
}
.recipient-toggle-left{
    display:flex;
    flex-direction:column;
    gap:4px;
}
.recipient-toggle-title{
    font-size:.92rem;
    font-weight:600;
    color:var(--brand-700);
}
.recipient-toggle-sub{
    font-size:.8rem;
    color:var(--muted);
}
.recipient-toggle-icon{
    font-size:1rem;
    color:var(--brand-700);
    transition:transform .18s ease;
}
.recipient-toggle.active .recipient-toggle-icon{
    transform:rotate(180deg);
}

.recipient-collapse{
    display:none;
    margin-top:10px;
}
.recipient-collapse.show{
    display:block;
}


.top-setup-grid{
    display:grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap:10px;
    align-items:end;
}
@media(max-width:768px){
    .top-setup-grid{
        grid-template-columns:1fr;
    }
}

.field-block{
    display:flex;
    flex-direction:column;
    gap:6px;
}

.optional-picker-btn{
    width:100%;
    min-height:42px;
    border:1px solid var(--line);
    background:#fff;
    border-radius:14px;
    padding:10px 12px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
    cursor:pointer;
    transition:.15s ease;
    text-align:left;
}
.optional-picker-btn:hover{
    border-color:var(--brand-line);
    background:var(--brand-soft);
}
.optional-picker-btn.active{
    border-color:var(--brand-line);
    background:var(--brand-soft);
}
.optional-picker-label{
    font-size:.92rem;
    font-weight:600;
    color:var(--brand-700);
}
.optional-picker-icon{
    color:var(--brand-700);
    font-size:1rem;
    transition:transform .18s ease;
}
.optional-picker-btn.active .optional-picker-icon{
    transform:rotate(180deg);
}

.optional-recipient-panel{
    display:none;
    margin-top:12px;
    border:1px solid var(--line);
    border-radius:16px;
    padding:12px;
    background:#f8fafc;
}
.optional-recipient-panel.show{
    display:block;
}

.compact-summary-row{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    margin-top:10px;
}
</style>

<div class="wrap">
    <div class="page-shell">
        <div class="header">
            <div class="title">
                <h2>Announcements</h2>
                <p>Post updates for students or scholarship scholars.</p>
            </div>
            <button class="btnx btnx-primary" id="openModalBtn">Create Announcement</button>
        </div>

        @if(session('success'))
            <div class="card" style="border-color:#bbf7d0;background:#f0fdf4;color:#166534;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="card" style="border-color:#fecaca;background:#fef2f2;color:#991b1b;">
                {{ session('error') }}
            </div>
        @endif

        @forelse($announcements as $post)
            <div class="card">
                <div class="space">
                    <div class="rowx">
                        <div class="avatar">
                            {{ strtoupper(substr($post->creator->firstname ?? 'C', 0, 1)) }}
                        </div>

                        <div>
                            <p class="name">{{ $post->creator->firstname ?? 'Coordinator' }} {{ $post->creator->lastname ?? '' }}</p>
                            <p class="sub">
                                <span>{{ $post->posted_at?->format('M d, Y h:i A') }}</span>

                                <span class="pill pill-blue">
                                    {{ match($post->audience){
                                        'all_students' => 'All Students',
                                        'specific_students' => 'Selected Students',
                                        'scholarship_scholars' => 'All Scholars in Scholarship',
                                        'specific_scholars' => 'Selected Scholars',
                                        'all_scholars' => 'All Scholars',
                                        default => 'Audience'
                                    } }}
                                </span>

                                @if($post->scholarship)
                                    <span class="pill">{{ $post->scholarship->scholarship_name }}</span>
                                @endif

                                <span class="pill">👁️ {{ $post->views_count ?? 0 }}</span>
                            </p>
                        </div>
                    </div>

                    <div style="display:flex; gap:8px; align-items:center;">
                        <a href="{{ route('coordinator.announcements.show', $post->id) }}"
                        class="btnx btnx-secondary btnx-sm">
                            Open
                        </a>

                        <form action="{{ route('coordinator.announcements.destroy', $post->id) }}"
                            method="POST"
                            onsubmit="return confirm('Delete this announcement? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button class="btnx btnx-danger btnx-sm" type="submit">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="post-title">{{ $post->title }}</div>
                <div class="post-body">{{ $post->description }}</div>

                @if($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Announcement image" class="post-image">
                @endif
            </div>
        @empty
            <div class="card" style="text-align:center; color:var(--muted);">
                No announcements yet.
            </div>
        @endforelse

        <div class="pagination-wrap">
            {{ $announcements->links() }}
        </div>
    </div>
</div>

<div class="modal-backdrop-custom" id="createModal">
    <div class="modal-custom">
        <div class="modal-head">
            <h3>Create Announcement</h3>
            <button class="modal-close" id="closeModalBtn" type="button">✕</button>
        </div>

        <form class="modal-body" action="{{ route('coordinator.announcements.store') }}" method="POST" enctype="multipart/form-data" id="announceForm">
            @csrf

            {{-- COMPACT SETUP --}}
            <div class="section-box setup-muted" style="margin-top:0;">
                <div class="section-title">Announcement Setup</div>

                <div class="top-setup-grid">
                    <div class="field-block">
                        <label class="sub">Target Group</label>
                        <select name="target_group" id="targetGroup" class="select compact-select" required>
                            <option value="students">All Students</option>
                            <option value="scholarship">Scholars</option>
                        </select>
                    </div>

                    <div class="field-block" id="scholarshipWrap">
                        <label class="sub">Scholarship</label>
                        <select name="scholarship_id" id="scholarshipSelect" class="select compact-select">
                            <option value="">Select scholarship</option>
                            @foreach($scholarships as $scholarship)
                                <option value="{{ $scholarship->id }}">{{ $scholarship->scholarship_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-block">
                        <label class="sub">Optional Recipients</label>
                        <button type="button" class="optional-picker-btn" id="recipientToggle">
                            <span class="optional-picker-label" id="recipientToggleTitle">Select specific students</span>
                            <span class="optional-picker-icon">⌄</span>
                        </button>
                    </div>
                </div>

                <div class="compact-summary-row">
                    <span class="summary-pill" id="summaryTarget">Target Group: All Students</span>
                    <span class="summary-pill hidden" id="summaryScholarship">Scholarship: -</span>
                </div>

                <div class="optional-recipient-panel" id="recipientCollapse">
                    <input class="input" id="searchInput" placeholder="Search by name, student id, or email..." autocomplete="off">

                    <div class="helper">
                        Leave this closed if you want to send the announcement to the whole selected group.
                    </div>

                    <div class="chips" id="chips"></div>

                    <div class="results" id="results">
                        <div class="helper">Loading recipients...</div>
                    </div>
                </div>
            </div>

            {{-- COMPACT RECIPIENT PICKER --}}
            {{-- <div class="section-box compact-recipient-box">
                <button type="button" class="recipient-toggle" id="recipientToggle">
                    <div class="recipient-toggle-left">
                        <span class="recipient-toggle-title" id="recipientToggleTitle">Select specific students (optional)</span>
                        <span class="recipient-toggle-sub">Leave this closed if you want to send to the whole selected group.</span>
                    </div>
                    <span class="recipient-toggle-icon">⌄</span>
                </button>

                <div class="recipient-collapse" id="recipientCollapse">
                    <input class="input" id="searchInput" placeholder="Search by name, student id, or email..." autocomplete="off">

                    <div class="chips" id="chips"></div>

                    <div class="results" id="results">
                        <div class="helper">Loading recipients...</div>
                    </div>
                </div>
            </div> --}}

            {{-- MAIN ANNOUNCEMENT AREA --}}
            <div class="section-box announcement-focus">
                <div class="section-title">Announcement Content</div>

                <div>
                    <label class="title-strong">Title</label>
                    <input class="input input-title" name="title" placeholder="Enter announcement title..." required>
                </div>

                <div style="margin-top:12px;">
                    <label class="title-strong">Description</label>
                    <textarea class="textarea textarea-announce" name="description" placeholder="Write your announcement here..." required></textarea>
                </div>

                <div style="margin-top:12px;">
                    <label class="sub">Optional Image</label>
                    <input class="input" type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
                </div>
            </div>

            <div class="footer-actions">
                <button type="button" class="btnx btnx-secondary" id="cancelBtn">Cancel</button>
                <button type="submit" class="btnx btnx-primary">Post Announcement</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const modal = document.getElementById('createModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    const targetGroup = document.getElementById('targetGroup');
    const scholarshipWrap = document.getElementById('scholarshipWrap');
    const scholarshipSelect = document.getElementById('scholarshipSelect');

    const summaryTarget = document.getElementById('summaryTarget');
    const summaryScholarship = document.getElementById('summaryScholarship');

    const recipientToggle = document.getElementById('recipientToggle');
    const recipientToggleTitle = document.getElementById('recipientToggleTitle');
    const recipientCollapse = document.getElementById('recipientCollapse');

    const searchInput = document.getElementById('searchInput');
    const results = document.getElementById('results');
    const chips = document.getElementById('chips');
    const form = document.getElementById('announceForm');

    let debounce = null;
    let selected = new Map();
    let recipientOpen = false;

    function openModal() {
        form.reset();
        selected.clear();
        recipientOpen = false;
        updateSelectedUI();
        updateRecipientCollapse();
        updateTargetUI();
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        form.reset();
        selected.clear();
        recipientOpen = false;
        updateSelectedUI();
        updateRecipientCollapse();
        updateTargetUI();
    }

    openBtn?.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);

    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    function updateSummary() {
        const group = targetGroup.value;

        if (group === 'students') {
            summaryTarget.textContent = 'Target Group: All Students';
            summaryScholarship.classList.add('hidden');
        } else {
            summaryTarget.textContent = 'Target Group: Scholars';

            const selectedText = scholarshipSelect.options[scholarshipSelect.selectedIndex]?.text || '-';

            if (scholarshipSelect.value) {
                summaryScholarship.textContent = `Scholarship: ${selectedText}`;
            } else {
                summaryScholarship.textContent = 'Scholarship: Not selected';
            }

            summaryScholarship.classList.remove('hidden');
        }
    }

    function updateRecipientLabels() {
        const group = targetGroup.value;

        if (group === 'students') {
            recipientToggleTitle.textContent = 'Select specific students';
        } else {
            recipientToggleTitle.textContent = 'Select specific scholars';
        }
    }

    function updateRecipientCollapse() {
        if (recipientOpen) {
            recipientCollapse.classList.add('show');
            recipientToggle.classList.add('active');
        } else {
            recipientCollapse.classList.remove('show');
            recipientToggle.classList.remove('active');
        }
    }

    function updateTargetUI() {
        const group = targetGroup.value;

        if (group === 'scholarship') {
            scholarshipWrap.classList.remove('hidden');
        } else {
            scholarshipWrap.classList.add('hidden');
        }

        selected.clear();
        updateSelectedUI();
        updateSummary();
        updateRecipientLabels();
        searchInput.value = '';

        if (recipientOpen) {
            if (group === 'students') {
                loadRecipients('');
            } else {
                if (scholarshipSelect.value) {
                    loadRecipients('');
                } else {
                    results.innerHTML = '<div class="helper">Please select a scholarship first.</div>';
                }
            }
        }
    }

    targetGroup.addEventListener('change', updateTargetUI);

    scholarshipSelect.addEventListener('change', function () {
        selected.clear();
        updateSelectedUI();
        updateSummary();
        searchInput.value = '';

        if (recipientOpen) {
            if (scholarshipSelect.value) {
                loadRecipients('');
            } else {
                results.innerHTML = '<div class="helper">Please select a scholarship first.</div>';
            }
        }
    });

    recipientToggle?.addEventListener('click', function () {
        recipientOpen = !recipientOpen;
        updateRecipientCollapse();

        if (recipientOpen) {
            const group = targetGroup.value;

            if (group === 'students') {
                loadRecipients('');
            } else {
                if (scholarshipSelect.value) {
                    loadRecipients('');
                } else {
                    results.innerHTML = '<div class="helper">Please select a scholarship first.</div>';
                }
            }
        }
    });

    function updateSelectedUI() {
        chips.innerHTML = '';

        selected.forEach((item) => {
            const chip = document.createElement('span');
            chip.className = 'chip';
            chip.innerHTML = `${item.label}<button type="button">×</button>`;

            chip.querySelector('button').addEventListener('click', () => {
                const key = `${targetGroup.value}:${item.id}`;
                selected.delete(key);
                updateSelectedUI();
            });

            chips.appendChild(chip);
        });

        document.querySelectorAll('.dyn-picked').forEach(el => el.remove());

        const group = targetGroup.value;

        if (group === 'students') {
            selected.forEach((item) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_users[]';
                input.value = item.id;
                input.className = 'dyn-picked';
                form.appendChild(input);
            });
        } else {
            selected.forEach((item) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_scholars[]';
                input.value = item.id;
                input.className = 'dyn-picked';
                form.appendChild(input);
            });
        }
    }

    async function fetchRecipients(query) {
        const group = targetGroup.value;

        if (group === 'students') {
            const url = new URL("{{ route('coordinator.announcements.recipients') }}", window.location.origin);
            url.searchParams.set('type', 'students');
            url.searchParams.set('q', query);

            const res = await fetch(url.toString(), {
                headers: { 'Accept': 'application/json' }
            });

            return await res.json();
        }

        if (group === 'scholarship') {
            const scholarshipId = scholarshipSelect.value;

            if (!scholarshipId) return [];

            const url = new URL("{{ route('coordinator.announcements.scholarship-scholars') }}", window.location.origin);
            url.searchParams.set('scholarship_id', scholarshipId);
            url.searchParams.set('q', query);

            const res = await fetch(url.toString(), {
                headers: { 'Accept': 'application/json' }
            });

            return await res.json();
        }

        return [];
    }

    function renderResults(list) {
        results.innerHTML = '';

        if (!list.length) {
            results.innerHTML = '<div class="helper">No results found.</div>';
            return;
        }

        list.forEach(item => {
            const id = item.id;
            const fullName = `${item.firstname ?? ''} ${item.lastname ?? ''}`.trim();
            const meta = `${item.student_id ?? ''}${item.bisu_email ? ' • ' + item.bisu_email : ''}`;
            const key = `${targetGroup.value}:${id}`;

            const row = document.createElement('div');
            row.className = 'res';
            row.innerHTML = `
                <div>
                    <b>${fullName || 'Unnamed'}</b><br>
                    <small>${meta}</small>
                </div>
                <div class="pill">${selected.has(key) ? 'Selected' : 'Select'}</div>
            `;

            row.addEventListener('click', () => {
                if (selected.has(key)) {
                    selected.delete(key);
                } else {
                    selected.set(key, { id, label: fullName });
                }

                updateSelectedUI();
                renderResults(list);
            });

            results.appendChild(row);
        });
    }

    async function loadRecipients(query = '') {
        results.innerHTML = '<div class="helper">Loading recipients...</div>';

        try {
            const data = await fetchRecipients(query);
            renderResults(data);
        } catch (e) {
            results.innerHTML = '<div class="helper">Failed to load search results.</div>';
        }
    }

    searchInput?.addEventListener('input', () => {
        clearTimeout(debounce);

        debounce = setTimeout(() => {
            loadRecipients(searchInput.value.trim());
        }, 250);
    });

    updateTargetUI();
})();
</script>
@endsection