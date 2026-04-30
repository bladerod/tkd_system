
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Templates — Taekwondo</title>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/app.css'])

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --ink:       #0d0d0d;
            --paper:     #faf8f5;
            --red:       #c0392b;
            --red-dark:  #96281b;
            --red-light: #f5e8e6;
            --gold:      #b8972a;
            --gold-light:#f9f3e3;
            --muted:     #6b6460;
            --border:    #e0dbd4;
            --white:     #ffffff;
            --shadow:    0 2px 16px rgba(0,0,0,.07);
        }




        /* ── Page shell ── */
        .page-wrap {
            margin-left: 270px; /* sidebar width */
            padding: 40px 36px;
        }

          /* ── Header ── */
        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--ink);
        }


        .page-title-block {}

        .page-eyebrow {
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--red);
            margin-bottom: 4px;
        }

        .page-title {
            font-family: 'Cinzel', serif;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.1;
            color: var(--ink);
        }

        .btn-create {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--red);
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background .18s;
            letter-spacing: .02em;
        }
        .btn-create:hover { background: var(--red-dark); }

        /* ── Filters bar ── */
        .filters-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .filter-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-right: 4px;
        }

        .filter-select {
            appearance: none;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 7px 32px 7px 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--ink);
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b6460'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            transition: border-color .15s;
        }
        .filter-select:focus { outline: none; border-color: var(--red); }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: var(--white);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            color: var(--muted);
            transition: all .15s;
        }
        .filter-pill.active, .filter-pill:hover {
            background: var(--ink);
            color: var(--white);
            border-color: var(--ink);
        }

        /* ── Stats row ── */
        .stats-row {
            display: flex;
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            flex: 1;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .stat-icon.red   { background: var(--red-light);  color: var(--red); }
        .stat-icon.gold  { background: var(--gold-light);  color: var(--gold); }
        .stat-icon.ink   { background: #efefee;             color: var(--ink); }

        .stat-num {
            font-family: 'Cinzel', serif;
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
            color: var(--ink);
        }
        .stat-lbl {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        /* ── Table ── */
        .table-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .cert-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cert-table thead {
            background: var(--ink);
            color: var(--white);
        }

        .cert-table th {
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 14px 16px;
            text-align: left;
        }

        .cert-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .12s;
        }
        .cert-table tbody tr:last-child { border-bottom: none; }
        .cert-table tbody tr:hover { background: #f9f7f4; }

        .cert-table td {
            padding: 14px 16px;
            vertical-align: middle;
            font-size: 13.5px;
        }

        .td-id {
            font-family: 'Cinzel', serif;
            font-size: 12px;
            color: var(--muted);
            font-weight: 600;
        }

        .td-name {
            font-weight: 500;
            color: var(--ink);
        }

        /* Type badge */
        .type-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .type-promotion  { background: var(--red-light);  color: var(--red); }
        .type-dan        { background: #0d0d0d1a;          color: var(--ink); }
        .type-competition{ background: var(--gold-light);  color: var(--gold); }
        .type-participation { background: #e8f0fe; color: #3a5fc8; }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .06em;
        }
        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .status-active  { color: #237a3b; }
        .status-active::before  { background: #2ecc71; }
        .status-draft   { color: var(--muted); }
        .status-draft::before   { background: var(--border); }

        /* Belt level chip */
        .belt-chip {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 2px;
            margin-right: 5px;
            vertical-align: middle;
        }

        /* Actions */
        .actions-cell {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all .15s;
            border: 1px solid transparent;
            cursor: pointer;
            background: transparent;
            font-family: 'DM Sans', sans-serif;
        }

        .action-edit {
            background: var(--ink);
            color: var(--white);
        }
        .action-edit:hover { background: #333; }

        .action-preview {
            border-color: var(--border);
            color: var(--muted);
        }
        .action-preview:hover { border-color: var(--ink); color: var(--ink); }

        .action-clone {
            border-color: var(--border);
            color: var(--muted);
        }
        .action-clone:hover { border-color: var(--gold); color: var(--gold); }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }
        .empty-state i { font-size: 36px; margin-bottom: 12px; color: var(--border); }
        .empty-state p { font-size: 14px; }

        /* Belt color map */
        .belt-white    { background: #f0f0f0; border: 1px solid #ccc; }
        .belt-yellow   { background: #f5c518; }
        .belt-green    { background: #27ae60; }
        .belt-blue     { background: #2980b9; }
        .belt-red      { background: #c0392b; }
        .belt-black    { background: #0d0d0d; }
    </style>
</head>
<body>

@include('includes.navbar')
@include('includes.sidebar')

<div class="page-wrap">

    <!-- Header -->
    <div class="page-header">
        <div class="page-title-block">
            <div class="page-eyebrow">&#9632; Taekwondo Certification System</div>
            <h1 class="page-title">Certificate Templates</h1>
        </div>
        <a href="/templates/create" class="btn-create">
            <i class="fa fa-plus"></i> New Template
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fa fa-certificate"></i></div>
            <div>
                <div class="stat-num">{{ $templates->count() }}</div>
                <div class="stat-lbl">Total Templates</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon gold"><i class="fa fa-medal"></i></div>
            <div>
                <div class="stat-num">{{ $templates->where('status','active')->count() }}</div>
                <div class="stat-lbl">Active</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon ink"><i class="fa fa-pen-ruler"></i></div>
            <div>
                <div class="stat-num">{{ $templates->where('status','draft')->count() }}</div>
                <div class="stat-lbl">Drafts</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fa fa-layer-group"></i></div>
            <div>
                <div class="stat-num">{{ $templates->groupBy('type')->count() }}</div>
                <div class="stat-lbl">Types</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-bar">
        <span class="filter-label">Filter:</span>

        <select class="filter-select" onchange="filterTable()" id="filterType">
            <option value="">All Types</option>
            <option value="Promotion">Belt Promotion</option>
            <option value="Dan">Black Belt (Dan)</option>
            <option value="Competition">Competition Award</option>
            <option value="Participation">Participation</option>
        </select>

        <select class="filter-select" onchange="filterTable()" id="filterBelt">
            <option value="">All Belt Levels</option>
            <option value="White">White</option>
            <option value="Yellow">Yellow</option>
            <option value="Green">Green</option>
            <option value="Blue">Blue</option>
            <option value="Red">Red</option>
            <option value="Black">Black</option>
        </select>

        <span class="filter-label" style="margin-left:8px;">Status:</span>
        <button class="filter-pill active" onclick="setStatus(this,'')">All</button>
        <button class="filter-pill" onclick="setStatus(this,'active')">Active</button>
        <button class="filter-pill" onclick="setStatus(this,'draft')">Draft</button>
    </div>

    <!-- Table -->
    <div class="table-card">
        <table class="cert-table" id="templateTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Template Name</th>
                    <th>Type</th>
                    <th>Belt Level</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $t)
                <tr data-type="{{ $t->type }}" data-belt="{{ $t->belt_level ?? '' }}" data-status="{{ $t->status ?? 'draft' }}">
                    <td class="td-id">#{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="td-name">{{ $t->name }}</td>
                    <td>
                        @php
                            $typeClass = match(strtolower($t->type ?? '')) {
                                'promotion'    => 'type-promotion',
                                'dan'          => 'type-dan',
                                'competition'  => 'type-competition',
                                'participation'=> 'type-participation',
                                default        => 'type-promotion',
                            };
                        @endphp
                        <span class="type-badge {{ $typeClass }}">{{ $t->type ?? '—' }}</span>
                    </td>
                    <td>
                        @if($t->belt_level)
                            @php $beltKey = 'belt-'.strtolower($t->belt_level); @endphp
                            <span class="belt-chip {{ $beltKey }}"></span>
                            {{ $t->belt_level }}
                        @else
                            <span style="color:var(--muted)">—</span>
                        @endif
                    </td>
                    <td>
                        @php $status = $t->status ?? 'draft'; @endphp
                        <span class="status-badge status-{{ $status }}">{{ ucfirst($status) }}</span>
                    </td>
                    <td style="color:var(--muted); font-size:12px;">
                        {{ $t->updated_at ? $t->updated_at->format('M d, Y') : '—' }}
                    </td>
                    <td>
                        <div class="actions-cell">
                            <a href="/templates/{{ $t->id }}/editor" class="action-btn action-edit">
                                <i class="fa fa-pen-to-square"></i> Edit
                            </a>
                            <a href="/templates/{{ $t->id }}/preview" class="action-btn action-preview">
                                <i class="fa fa-eye"></i> Preview
                            </a>
                            <button onclick="cloneTemplate({{ $t->id }})" class="action-btn action-clone">
                                <i class="fa fa-clone"></i> Clone
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fa fa-certificate"></i>
                            <p>No certificate templates yet. <a href="/templates/create" style="color:var(--red);">Create your first one.</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
    let currentStatus = '';

    function filterTable() {
        const type  = document.getElementById('filterType').value.toLowerCase();
        const belt  = document.getElementById('filterBelt').value.toLowerCase();
        document.querySelectorAll('#templateTable tbody tr[data-type]').forEach(row => {
            const rType   = (row.dataset.type   || '').toLowerCase();
            const rBelt   = (row.dataset.belt   || '').toLowerCase();
            const rStatus = (row.dataset.status || '').toLowerCase();
            const matchType   = !type   || rType.includes(type);
            const matchBelt   = !belt   || rBelt.includes(belt);
            const matchStatus = !currentStatus || rStatus === currentStatus;
            row.style.display = (matchType && matchBelt && matchStatus) ? '' : 'none';
        });
    }

    function setStatus(btn, status) {
        currentStatus = status;
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        filterTable();
    }

    function cloneTemplate(id) {
        if (!confirm('Clone this template?')) return;
        fetch(`/templates/${id}/clone`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(r => r.json()).then(data => {
            if (data.redirect) window.location.href = data.redirect;
            else location.reload();
        }).catch(() => alert('Clone failed.'));
    }
</script>

</body>
</html>

