@extends('layouts.app')

@section('title', 'Shop Floor')
@section('breadcrumb')
    Production / <span class="current">Shop Floor</span>
@endsection

@push('styles')
<style>
.shop-floor-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
}

.wo-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
    box-shadow: var(--shadow-sm);
}

.wo-card:hover {
    box-shadow: var(--shadow);
    transform: translateY(-2px);
}

.wo-card-header {
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--border);
}

.wo-card-body { padding: 14px 16px; }

.wo-card-footer {
    padding: 10px 16px;
    background: #f8fafc;
    border-top: 1px solid var(--border);
    display: flex;
    gap: 8px;
}

.progress-ring {
    position: relative;
    width: 60px; height: 60px;
    flex-shrink: 0;
}

.progress-ring svg { transform: rotate(-90deg); }

.station-column {
    background: var(--bg);
    border-radius: 12px;
    padding: 14px;
}

.station-header {
    font-size: 13px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-industry-windows" style="color:var(--warning);margin-right:10px"></i>Shop Floor</h1>
        <p class="page-subtitle">Real-time production status — track, clock in/out, log materials</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <!-- View toggles -->
        <div class="d-flex gap-1" style="border:1px solid var(--border);border-radius:8px;overflow:hidden">
            <button class="btn btn-secondary btn-sm" onclick="setView('kanban')" id="kanbanBtn" style="border-radius:0;border:none;border-right:1px solid var(--border)">
                <i class="fa-solid fa-columns-3"></i> Kanban
            </button>
            <button class="btn btn-secondary btn-sm" onclick="setView('card')" id="cardBtn" style="border-radius:0;border:none">
                <i class="fa-solid fa-grid-2"></i> Cards
            </button>
        </div>
        <select class="form-select" style="width:auto;font-size:13px">
            <option>All Work Centers</option>
            <option>Assembly</option>
            <option>Fabrication</option>
            <option>Testing</option>
            <option>Outsource</option>
        </select>
        <a href="{{ route('production.work-orders.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> New WO
        </a>
    </div>
</div>

<!-- Live Stats Bar -->
<div style="display:flex;gap:16px;margin-bottom:20px;overflow-x:auto;padding-bottom:4px">
    @php
    $liveStats = [
        ['Active Workers', 14, 'fa-users', 'success'],
        ['Orders In Progress', 47, 'fa-gears', 'warning'],
        ['Completed Today', 8, 'fa-check-double', 'success'],
        ['On Hold', 3, 'fa-pause-circle', 'warning'],
        ['Pending QC', 6, 'fa-magnifying-glass', 'info'],
        ['Avg Efficiency', '87%', 'fa-chart-line', 'accent'],
    ];
    @endphp
    @foreach($liveStats as $s)
    <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:12px 18px;display:flex;align-items:center;gap:12px;white-space:nowrap;box-shadow:var(--shadow-sm);flex-shrink:0">
        <i class="fa-solid {{ $s[2] }}" style="color:var(--{{ $s[3] }});font-size:16px"></i>
        <div>
            <div style="font-size:18px;font-weight:700;color:var(--text)">{{ $s[1] }}</div>
            <div style="font-size:11px;color:var(--text-muted)">{{ $s[0] }}</div>
        </div>
    </div>
    @endforeach
    <div style="margin-left:auto;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);border-radius:10px;padding:12px 18px;display:flex;align-items:center;gap:8px;flex-shrink:0">
        <div style="width:8px;height:8px;background:var(--success);border-radius:50%;animation:pulse 2s infinite"></div>
        <span style="font-size:13px;font-weight:600;color:var(--success)">Live Updates Active</span>
    </div>
</div>

<!-- Kanban View -->
<div id="kanbanView">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px">
        @php
        $stations = [
            ['Queue', 'secondary', 'fa-inbox', [
                ['WO-0298','Enclosure Fab','Pacific Steel','100 EA','2025-07-25',''],
                ['WO-0295','Bracket Kit','Nexus Parts','50 EA','2025-07-22',''],
                ['WO-0293','Connector Set','TechCorp','200 EA','2025-07-30',''],
            ]],
            ['Assembly', 'accent', 'fa-screwdriver-wrench', [
                ['WO-0300','Wire Harness 4A','SO-2025-0847','25 EA','2025-07-15','38%'],
                ['WO-0297','Sensor Array','SO-2025-0842','5 EA','2025-07-05','90%'],
            ]],
            ['Testing / QC', 'purple', 'fa-flask', [
                ['WO-0301','PCB Assembly X72','SO-2025-0845','50 EA','2025-07-12','92%'],
                ['WO-0294','Motor Ctrl v3','Acme Industries','10 EA','2025-07-18','65%'],
            ]],
            ['Complete', 'success', 'fa-check-circle', [
                ['WO-0296','Power Supply Unit','Internal','30 EA','2025-06-30','100%'],
                ['WO-0292','LED Driver Brd','Delta Systems','15 EA','2025-06-28','100%'],
            ]],
        ];
        @endphp

        @foreach($stations as $station)
        <div class="station-column">
            <div class="station-header">
                <div style="display:flex;align-items:center;gap:8px">
                    <i class="fa-solid {{ $station[2] }}" style="color:var(--{{ $station[1] }});font-size:13px"></i>
                    {{ $station[0] }}
                </div>
                <span class="badge badge-{{ $station[1] }}">{{ count($station[3]) }}</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:10px">
                @foreach($station[3] as $wo)
                <div class="wo-card">
                    <div class="wo-card-header" style="background:var(--bg)">
                        <div>
                            <a href="#" style="font-family:'DM Mono',monospace;font-size:12px;color:var(--accent);font-weight:600">{{ $wo[0] }}</a>
                            <div style="font-size:10.5px;color:var(--text-muted)">Due: {{ $wo[4] }}</div>
                        </div>
                        @if($wo[5])
                        <div style="text-align:center">
                            <div style="font-size:16px;font-weight:700;color:{{ $wo[5] === '100%' ? 'var(--success)' : ($wo[5] >= '90%' ? 'var(--warning)' : 'var(--accent)') }}">{{ $wo[5] }}</div>
                            <div style="font-size:9px;color:var(--text-muted)">done</div>
                        </div>
                        @endif
                    </div>
                    <div class="wo-card-body">
                        <div style="font-size:13.5px;font-weight:600;margin-bottom:4px">{{ $wo[1] }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">{{ $wo[2] }}</div>
                        <div style="margin-top:8px;display:flex;justify-content:space-between;font-size:12px">
                            <span style="color:var(--text-muted)">Qty:</span>
                            <span style="font-weight:600;font-family:'DM Mono',monospace">{{ $wo[3] }}</span>
                        </div>
                        @if($wo[5] && $wo[5] !== '100%')
                        <div style="margin-top:8px">
                            <div style="height:4px;background:#f1f5f9;border-radius:2px;overflow:hidden">
                                <div style="height:100%;width:{{ $wo[5] }};background:var(--{{ $station[1] }});border-radius:2px;transition:width 0.5s"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="wo-card-footer">
                        <button class="btn btn-success btn-sm" style="flex:1;font-size:11.5px" onclick="clockIn('{{ $wo[0] }}')">
                            <i class="fa-solid fa-play"></i> Clock In
                        </button>
                        <button class="btn btn-secondary btn-sm btn-icon" title="View">
                            <i class="fa-solid fa-eye" style="font-size:10px"></i>
                        </button>
                        <button class="btn btn-secondary btn-sm btn-icon" title="Print Traveler">
                            <i class="fa-solid fa-print" style="font-size:10px"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Clock In/Out Modal -->
<div class="modal fade" id="clockModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius:12px;border:1px solid var(--border)">
            <div class="modal-header" style="border-bottom:1px solid var(--border);padding:16px 20px">
                <h5 class="modal-title" style="font-size:15px;font-weight:600">
                    <i class="fa-solid fa-clock" style="color:var(--accent);margin-right:8px"></i>Clock Into Work Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px">
                <div style="text-align:center;margin-bottom:16px">
                    <div style="font-family:'DM Mono',monospace;font-size:28px;font-weight:700;color:var(--accent)" id="clockTimer">00:00:00</div>
                    <div style="font-size:12px;color:var(--text-muted)" id="clockWONum">—</div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Operation</label>
                    <select class="form-select">
                        <option>Assembly</option>
                        <option>Soldering</option>
                        <option>Testing</option>
                        <option>Inspection</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                </div>
            </div>
            <div class="modal-footer" style="padding:12px 20px;gap:8px">
                <button class="btn btn-danger w-100" onclick="clockOut()">
                    <i class="fa-solid fa-stop"></i> Clock Out
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let clockInterval;
let clockSeconds = 0;

function clockIn(woNum) {
    document.getElementById('clockWONum').textContent = woNum;
    clockSeconds = 0;
    clearInterval(clockInterval);
    clockInterval = setInterval(() => {
        clockSeconds++;
        const h = String(Math.floor(clockSeconds/3600)).padStart(2,'0');
        const m = String(Math.floor((clockSeconds%3600)/60)).padStart(2,'0');
        const s = String(clockSeconds%60).padStart(2,'0');
        document.getElementById('clockTimer').textContent = `${h}:${m}:${s}`;
    }, 1000);
    new bootstrap.Modal(document.getElementById('clockModal')).show();
    showToast('Clocked in to ' + woNum, 'success');
}

function clockOut() {
    clearInterval(clockInterval);
    bootstrap.Modal.getInstance(document.getElementById('clockModal'))?.hide();
    showToast('Clocked out — time recorded', 'success');
}

function setView(v) {
    document.getElementById('kanbanView').style.display = v === 'kanban' ? '' : 'none';
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
</script>
@endpush
