<div id="page-content" class="page-wrapper clearfix organogram-page-wrapper">
    <!-- Top Control Bar -->
    <div class="organogram-topbar">
        <div class="organogram-title-area">
            <div class="organogram-icon-box">
                <i data-feather="git-pull-request" class="icon-20"></i>
            </div>
            <div>
                <h4 class="organogram-main-title">
                    <?php echo app_lang("organogram_chart"); ?>
                    <span class="badge organogram-badge-gov">Dual-Branching Governance Hierarchy</span>
                </h4>
                <p class="organogram-subtitle">
                    Paper-style hierarchical approval engine: Level A Apex &rarr; Level B Directors &rarr; Level C Department Heads &rarr; Level D Operations.
                </p>
            </div>
        </div>

        <div class="organogram-actions">
            <!-- Canvas Controls -->
            <div class="btn-group organogram-zoom-group" role="group">
                <button type="button" class="btn btn-default btn-sm" id="btn-zoom-in" title="Zoom In">
                    <i data-feather="zoom-in" class="icon-14"></i>
                </button>
                <button type="button" class="btn btn-default btn-sm" id="btn-zoom-reset" title="Reset Zoom">
                    <span id="zoom-level-text">100%</span>
                </button>
                <button type="button" class="btn btn-default btn-sm" id="btn-zoom-out" title="Zoom Out">
                    <i data-feather="zoom-out" class="icon-14"></i>
                </button>
                <button type="button" class="btn btn-default btn-sm" id="btn-fit-canvas" title="Fit to Screen">
                    <i data-feather="maximize" class="icon-14"></i>
                </button>
            </div>

            <!-- Workflow Simulator Trigger -->
            <button type="button" class="btn btn-warning btn-sm" id="btn-open-simulator">
                <i data-feather="play" class="icon-14 mr5"></i> <?php echo app_lang("organogram_simulate"); ?>
            </button>

            <?php if ($can_manage): ?>
                <!-- Auto-Arrange Dual Branching Tree -->
                <button type="button" class="btn btn-primary btn-sm" id="btn-auto-layout" title="Realign dual-branching hierarchy tree">
                    <i data-feather="layout" class="icon-14 mr5"></i> <?php echo app_lang("organogram_auto_layout"); ?>
                </button>

                <!-- Reset Organogram -->
                <button type="button" class="btn btn-danger-outline btn-sm" id="btn-reset-hierarchy" title="Reset to standard NCS dual-branching hierarchy">
                    <i data-feather="rotate-ccw" class="icon-14"></i>
                </button>
            <?php endif; ?>

            <!-- Auto-Save Status Badge -->
            <div class="organogram-autosave-indicator" id="autosave-status">
                <i data-feather="check" class="icon-12 text-success mr5"></i>
                <span>Auto-saved</span>
            </div>

            <!-- Palette Toggle (Mobile/Responsive) -->
            <button type="button" class="btn btn-default btn-sm d-md-none" id="btn-toggle-palette">
                <i data-feather="sidebar" class="icon-14"></i>
            </button>
        </div>
    </div>

    <!-- Main Workspace: Canvas (Left/Center) + Palette (Right) -->
    <div class="organogram-workspace">
        
        <!-- Interactive Canvas Viewport -->
        <div class="organogram-canvas-container" id="organogram-container">
            <!-- Studio Paper Gray Canvas Background -->
            <div class="organogram-canvas-bg" id="organogram-bg"></div>

            <!-- World Container (Scalable & Pannable) -->
            <div class="organogram-world" id="organogram-world">
                <!-- SVG Orthogonal Stepped Connectors Layer -->
                <svg class="organogram-svg-layer" id="organogram-svg">
                    <defs>
                        <!-- Arrow Marker: Standard bottom-to-top workflow flow -->
                        <marker id="arrow-standard" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                            <path d="M 0 1.5 L 8 5 L 0 8.5 z" fill="#64748b" />
                        </marker>
                        <!-- Arrow Marker: Active simulation tracer -->
                        <marker id="arrow-active" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse">
                            <path d="M 0 1.5 L 8 5 L 0 8.5 z" fill="#f59e0b" />
                        </marker>
                        <!-- Drop Shadow Filter for Paper Look -->
                        <filter id="line-shadow" x="-10%" y="-10%" width="120%" height="120%">
                            <feDropShadow dx="0" dy="2" stdDeviation="1.5" flood-color="#94a3b8" flood-opacity="0.25" />
                        </filter>
                    </defs>
                    <g id="connectors-group"></g>
                </svg>

                <!-- Paper Chips Nodes Layer -->
                <div class="organogram-nodes-layer" id="organogram-nodes"></div>
            </div>

            <!-- Infographic Legend Overlay -->
            <div class="organogram-canvas-hint">
                <div class="hint-item"><span class="level-indicator-chip level-a-tag">LEVEL A</span> Apex General Secretary</div>
                <div class="hint-item"><span class="level-indicator-chip level-b-tag">LEVEL B</span> Directors (Dual Branch)</div>
                <div class="hint-item"><span class="level-indicator-chip level-c-tag">LEVEL C</span> Department Heads</div>
                <div class="hint-item"><span class="level-indicator-chip level-d-tag">LEVEL D</span> Operations & Officers</div>
                <div class="hint-item text-muted"><i data-feather="arrow-up" class="icon-12 text-warning mr5"></i> Approval flows bottom-to-top</div>
            </div>

            <!-- Empty Canvas Placeholder -->
            <div class="organogram-empty-state" id="organogram-empty-state" style="display: none;">
                <i data-feather="move" class="empty-icon"></i>
                <h5>Canvas is Empty</h5>
                <p>Drag offices and branches from the right palette onto this canvas to construct the dual-branching hierarchy.</p>
            </div>
        </div>

        <!-- Right Side Palette: All Departments (Branches) and Roles (Offices) -->
        <div class="organogram-palette-sidebar" id="organogram-palette">
            <div class="palette-header">
                <div class="palette-header-title">
                    <i data-feather="grid" class="icon-16 text-primary mr5"></i>
                    <span class="font-bold"><?php echo app_lang("organogram_palette"); ?></span>
                    <span class="badge bg-primary text-white ml5" id="unplaced-counter">0</span>
                </div>
                <span class="palette-subtitle">Unplaced offices pool &bull; Zero duplication</span>
                
                <!-- Quick Search Filter -->
                <div class="palette-search-wrapper mt10">
                    <i data-feather="search" class="search-icon"></i>
                    <input type="text" id="palette-search" class="form-control form-control-sm palette-search-input" placeholder="<?php echo app_lang("organogram_search_roles"); ?>" autocomplete="off" />
                </div>
            </div>

            <!-- Palette Scrollable Body -->
            <div class="palette-body" id="palette-departments-container">
                <div class="palette-loading text-center p20 text-muted">
                    <span class="spinner-border spinner-border-sm mr5"></span> Loading offices...
                </div>
            </div>

            <!-- Palette Footer Info -->
            <div class="palette-footer">
                <i data-feather="info" class="icon-12 text-info mr5"></i>
                <small class="text-muted">Drag any office onto canvas. Lines attach and save automatically.</small>
            </div>
        </div>
    </div>

    <!-- Workflow Approval Simulator Drawer / Modal -->
    <div class="modal fade" id="workflow-simulator-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content organogram-sim-modal">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="sim-modal-icon mr10">
                            <i data-feather="send" class="icon-20 text-warning"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-bold mb0">Dual-Branching Approval Path Tracer</h5>
                            <small class="text-muted">Simulate sequential bottom-to-top requisition routing through the organogram hierarchy</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p20">
                    <form id="workflow-sim-form" class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label font-bold text-dark">Initiating Subordinate Office</label>
                            <select id="sim-role-select" class="form-select" required>
                                <option value="">-- Choose initiating office --</option>
                            </select>
                            <small class="text-muted">Where the document / requisition originates</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-bold text-dark">Requisition Amount (UGX)</label>
                            <div class="input-group">
                                <span class="input-group-text font-bold">UGX</span>
                                <input type="number" id="sim-amount" class="form-control" value="80000000" min="0" step="50000" />
                            </div>
                            <small class="text-muted">Determines financial threshold escalation</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label font-bold text-dark">Document Type</label>
                            <select id="sim-doc-type" class="form-select">
                                <option value="procurement_form_5">PPDA Form 5 Requisition</option>
                                <option value="payment_voucher">Payment Voucher</option>
                                <option value="activity_memo">Activity / Travel Memo</option>
                                <option value="leave_application">Leave Application</option>
                                <option value="fixed_asset_req">Capital Expenditure</option>
                            </select>
                        </div>

                        <div class="col-12 text-end mt15">
                            <button type="submit" class="btn btn-warning px-4 font-bold" id="btn-run-sim">
                                <i data-feather="play-circle" class="icon-16 mr5"></i> Trace Approval Pathway
                            </button>
                        </div>
                    </form>

                    <!-- Simulation Result Ladder View -->
                    <div id="sim-results-container" class="mt20" style="display: none;">
                        <hr class="my15" />
                        <div class="d-flex justify-content-between align-items-center mb15">
                            <h6 class="font-bold text-dark mb0">
                                <i data-feather="check-circle" class="icon-16 text-success mr5"></i>
                                Generated Sequential Approval Chain
                            </h6>
                            <span class="badge bg-success" id="sim-total-steps-badge">0 Steps</span>
                        </div>

                        <!-- Step Cards Ladder -->
                        <div class="approval-ladder-container" id="sim-ladder-steps"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-highlight-on-canvas" style="display:none;">
                        <i data-feather="eye" class="icon-14 mr5"></i> Highlight on Canvas
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Organogram Stylesheet: Exact Match to Paper-Style Infographic Reference -->
<style>
/* ==========================================================================
   PAPER-STYLE ORGANOGRAM DESIGN SYSTEM
   ========================================================================== */

.organogram-page-wrapper {
    padding: 0 !important;
    height: calc(100vh - 70px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: #eef2f6;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Header Bar */
.organogram-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 24px;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    z-index: 20;
    flex-shrink: 0;
}

.organogram-title-area {
    display: flex;
    align-items: center;
    gap: 12px;
}

.organogram-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #0d9488 0%, #047857 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.3);
}

.organogram-main-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.organogram-badge-gov {
    font-size: 0.7rem;
    font-weight: 600;
    background: #f1f5f9;
    color: #0f766e;
    border: 1px solid #ccfbf1;
    padding: 2px 8px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.organogram-subtitle {
    font-size: 0.78rem;
    color: #64748b;
    margin: 2px 0 0 0;
}

.organogram-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.organogram-zoom-group {
    background: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}

.organogram-zoom-group button {
    border: none !important;
    background: transparent !important;
    padding: 6px 10px;
    color: #475569;
}

.organogram-zoom-group button:hover {
    background: #e2e8f0 !important;
    color: #0f172a;
}

#zoom-level-text {
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 42px;
    display: inline-block;
    text-align: center;
}

.organogram-autosave-indicator {
    display: flex;
    align-items: center;
    font-size: 0.72rem;
    color: #059669;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
}

/* Main Split Workspace */
.organogram-workspace {
    display: flex;
    flex: 1;
    position: relative;
    overflow: hidden;
}

/* Canvas Viewport */
.organogram-canvas-container {
    flex: 1;
    position: relative;
    overflow: hidden;
    cursor: grab;
    user-select: none;
    background: radial-gradient(circle at center, #f8fafc 0%, #e2e8f0 100%);
}

.organogram-canvas-container:active {
    cursor: grabbing;
}

/* Studio Paper Backdrop with Subtle Radial Vignette */
.organogram-canvas-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    background-image: radial-gradient(circle, #cbd5e1 1.2px, transparent 1.2px);
    background-size: 28px 28px;
    pointer-events: none;
    opacity: 0.6;
}

/* Scalable World Layer */
.organogram-world {
    position: absolute;
    top: 0;
    left: 0;
    width: 6000px;
    height: 6000px;
    transform-origin: 0 0;
    will-change: transform;
}

/* SVG Orthogonal Stepped Lines Layer */
.organogram-svg-layer {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 5;
}

.workflow-connector-path {
    fill: none;
    stroke: #94a3b8;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
    filter: url(#line-shadow);
    transition: stroke 0.3s, stroke-width 0.3s;
}

.workflow-connector-path.active-path {
    stroke: #f59e0b !important;
    stroke-width: 4 !important;
    filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.7)) !important;
    stroke-dasharray: 8 4;
    animation: flowDash 1s linear infinite;
}

@keyframes flowDash {
    to {
        stroke-dashoffset: -12;
    }
}

/* ==========================================================================
   PAPER CAPSULE CHIPS DESIGN (Exact match to reference infographic)
   ========================================================================== */

.organogram-nodes-layer {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
}

/* Universal Base Paper Chip */
.paper-chip-card {
    position: absolute;
    background: #ffffff;
    border-radius: 9999px;
    box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.08), 0 4px 10px -2px rgba(0, 0, 0, 0.04);
    cursor: move;
    transition: transform 0.15s ease, box-shadow 0.2s ease;
    user-select: none;
    display: flex;
    align-items: center;
}

.paper-chip-card:hover {
    box-shadow: 0 16px 32px -4px rgba(0, 0, 0, 0.14), 0 6px 12px -2px rgba(0, 0, 0, 0.06);
    transform: translateY(-2px);
    z-index: 30;
}

.paper-chip-card.is-dragging {
    opacity: 0.88;
    box-shadow: 0 24px 38px -5px rgba(0, 0, 0, 0.22);
    z-index: 100;
}

.paper-chip-card.active-highlight {
    box-shadow: 0 0 0 4px #f59e0b, 0 16px 32px -4px rgba(245, 158, 11, 0.3) !important;
    animation: chipPulse 1.5s ease-in-out infinite;
}

@keyframes chipPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.03); }
}

/* Folded 3D Ribbon Level Tag at Top-Right of Chip */
.chip-ribbon-tag {
    position: absolute;
    top: -4px;
    right: 28px;
    font-size: 0.62rem;
    font-weight: 800;
    color: #ffffff;
    padding: 2px 10px;
    border-radius: 4px;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    z-index: 15;
}

.chip-ribbon-tag::after {
    content: '';
    position: absolute;
    right: -4px;
    bottom: -4px;
    border-width: 4px 0 0 4px;
    border-style: solid;
    border-color: transparent transparent transparent rgba(0, 0, 0, 0.3);
}

/* Level Colors for Ribbon and Accents */
.level-a-ribbon { background: #008080; } /* Teal */
.level-b-ribbon { background: #16a34a; } /* Green */
.level-c-ribbon { background: #f59e0b; } /* Gold/Amber */
.level-d-ribbon { background: #ea580c; } /* Orange */

/* Left Crescent Accent Arc wrapping around the avatar */
.chip-crescent-arc {
    position: absolute;
    left: -4px;
    top: 50%;
    transform: translateY(-50%);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border-left: 6px solid #008080;
    pointer-events: none;
    z-index: 2;
}

.level-a-crescent { border-left-color: #008080; }
.level-b-crescent { border-left-color: #16a34a; }
.level-c-crescent { border-left-color: #f59e0b; }
.level-d-crescent { border-left-color: #ea580c; }

/* --------------------------------------------------------------------------
   LEVEL A: Apex Capsule with Top Overlapping Photo
   -------------------------------------------------------------------------- */
.paper-chip-level-a {
    width: 270px;
    height: 66px;
    padding-left: 20px;
    padding-right: 15px;
    border: 1px solid #e2e8f0;
}

.level-a-apex-wrapper {
    position: absolute;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Prominent circular executive photo on top of Level A */
.level-a-photo-badge {
    width: 78px;
    height: 78px;
    border-radius: 50%;
    border: 4px solid #ffffff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.14);
    overflow: hidden;
    background: #e2e8f0;
    margin-bottom: -18px;
    z-index: 20;
    position: relative;
}

.level-a-photo-badge img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* --------------------------------------------------------------------------
   LEVEL B: Executive Dual Branching Chips (Width: 280px, Height: 68px)
   -------------------------------------------------------------------------- */
.paper-chip-level-b {
    width: 280px;
    height: 68px;
    padding: 6px 16px 6px 12px;
    border: 1px solid #e2e8f0;
}

.chip-avatar-wrapper {
    position: relative;
    width: 50px;
    height: 50px;
    margin-right: 12px;
    flex-shrink: 0;
}

.chip-avatar-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 3px solid #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    object-fit: cover;
    background: #e2e8f0;
}

.chip-content-body {
    flex: 1;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.chip-title {
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2;
}

.title-color-a { color: #0f766e; }
.title-color-b { color: #15803d; }
.title-color-c { color: #b45309; }
.title-color-d { color: #c2410c; }

.chip-subtitle {
    font-size: 0.65rem;
    color: #94a3b8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 500;
    margin-top: 2px;
}

.chip-holder-name {
    font-size: 0.63rem;
    color: #64748b;
    font-weight: 600;
}

/* Quick hover action buttons */
.chip-hover-actions {
    display: none;
    position: absolute;
    bottom: -10px;
    right: 16px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    border: 1px solid #e2e8f0;
    padding: 2px 6px;
    gap: 4px;
    z-index: 25;
}

.paper-chip-card:hover .chip-hover-actions {
    display: flex;
}

.chip-mini-btn {
    border: none;
    background: transparent;
    padding: 2px 4px;
    border-radius: 4px;
    color: #64748b;
    cursor: pointer;
    font-size: 0.65rem;
}

.chip-mini-btn:hover {
    color: #0f172a;
    background: #f1f5f9;
}

.chip-mini-btn.btn-del:hover {
    color: #e11d48;
    background: #ffe4e6;
}

/* --------------------------------------------------------------------------
   LEVEL C: Department Heads Chips (Width: 260px, Height: 62px)
   -------------------------------------------------------------------------- */
.paper-chip-level-c {
    width: 260px;
    height: 62px;
    padding: 6px 14px 6px 10px;
    border: 1px solid #e2e8f0;
}

.paper-chip-level-c .chip-avatar-wrapper {
    width: 44px;
    height: 44px;
    margin-right: 10px;
}

.paper-chip-level-c .chip-avatar-img {
    width: 44px;
    height: 44px;
}

/* --------------------------------------------------------------------------
   LEVEL D: Operational Officers Chips
   -------------------------------------------------------------------------- */
.paper-chip-level-d {
    width: 230px;
    height: 56px;
    padding: 4px 12px 4px 8px;
    border: 1px solid #e2e8f0;
}

.paper-chip-level-d .chip-avatar-wrapper {
    width: 38px;
    height: 38px;
    margin-right: 8px;
}

.paper-chip-level-d .chip-avatar-img {
    width: 38px;
    height: 38px;
}

/* Connecting Ports for Orthogonal Lines */
.chip-port {
    position: absolute;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #64748b;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.2s;
}

.paper-chip-card:hover .chip-port {
    opacity: 1;
}

.chip-port.port-top { top: -3px; }
.chip-port.port-bottom { bottom: -3px; }

/* Legend Level Chips */
.organogram-canvas-hint {
    position: absolute;
    bottom: 16px;
    left: 20px;
    background: rgba(255, 255, 255, 0.94);
    backdrop-filter: blur(8px);
    padding: 8px 16px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 14px;
    z-index: 15;
    pointer-events: none;
}

.level-indicator-chip {
    font-size: 0.62rem;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 4px;
    color: #ffffff;
}

.level-a-tag { background: #008080; }
.level-b-tag { background: #16a34a; }
.level-c-tag { background: #f59e0b; }
.level-d-tag { background: #ea580c; }

/* Empty Canvas Placeholder */
.organogram-empty-state {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #94a3b8;
    pointer-events: none;
    z-index: 10;
}

.organogram-empty-state .empty-icon {
    width: 48px;
    height: 48px;
    margin-bottom: 12px;
    opacity: 0.4;
}

/* ==========================================================================
   RIGHT PALETTE SIDEBAR
   ========================================================================== */

.organogram-palette-sidebar {
    width: 320px;
    background: #ffffff;
    border-left: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    z-index: 20;
    box-shadow: -2px 0 6px -1px rgba(0, 0, 0, 0.04);
    flex-shrink: 0;
}

.palette-header {
    padding: 14px 16px;
    border-bottom: 1px solid #e2e8f0;
    background: #ffffff;
}

.palette-header-title {
    display: flex;
    align-items: center;
    font-size: 0.95rem;
    color: #0f172a;
}

.palette-subtitle {
    display: block;
    font-size: 0.72rem;
    color: #64748b;
    margin-top: 2px;
}

.palette-search-wrapper {
    position: relative;
}

.palette-search-wrapper .search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 14px;
    height: 14px;
    color: #94a3b8;
}

.palette-search-input {
    padding-left: 32px;
    border-radius: 6px;
    border-color: #cbd5e1;
    font-size: 0.8rem;
}

.palette-body {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.palette-dept-card {
    margin-bottom: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    background: #ffffff;
}

.palette-dept-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8fafc;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
}

.palette-role-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 7px 10px;
    margin-bottom: 4px;
    background: #ffffff;
    border: 1px dashed #cbd5e1;
    border-radius: 20px;
    cursor: grab;
    transition: all 0.15s ease;
}

.palette-role-item:hover {
    background: #f0fdf4;
    border-color: #16a34a;
    border-style: solid;
    transform: translateX(-2px);
    box-shadow: 0 2px 6px rgba(22, 163, 74, 0.15);
}

.palette-role-item.is-dragging {
    opacity: 0.5;
    border-color: #0284c7;
}

.role-info-left {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow: hidden;
}

.role-drag-handle {
    color: #94a3b8;
    cursor: grab;
}

.role-title-text {
    font-size: 0.75rem;
    font-weight: 700;
    color: #1e293b;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 175px;
}

.palette-footer {
    padding: 10px 14px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
}

/* ==========================================================================
   SIMULATOR MODAL & APPROVAL LADDER
   ========================================================================== */

.organogram-sim-modal {
    border-radius: 12px;
    border: none;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.sim-modal-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #fef3c7;
    display: flex;
    align-items: center;
    justify-content: center;
}

.approval-ladder-container {
    position: relative;
    padding-left: 28px;
}

.approval-ladder-container::before {
    content: '';
    position: absolute;
    left: 11px;
    top: 15px;
    bottom: 25px;
    width: 2px;
    background: #cbd5e1;
}

.ladder-step-card {
    position: relative;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 14px;
    margin-bottom: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.ladder-step-marker {
    position: absolute;
    left: -28px;
    top: 14px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #0284c7;
    color: #ffffff;
    font-size: 0.72rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 0 3px #ffffff;
}

.ladder-step-marker.step-initiator { background: #16a34a; }
.ladder-step-marker.step-apex { background: #008080; }

.ladder-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.ladder-step-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f172a;
}

.ladder-step-action {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
}

.action-initiate { background: #dcfce7; color: #15803d; }
.action-review { background: #e0f2fe; color: #0369a1; }
.action-approve { background: #fef3c7; color: #b45309; }
.action-final { background: #ccfbf1; color: #0f766e; }

.ladder-step-details {
    font-size: 0.75rem;
    color: #64748b;
    display: flex;
    gap: 16px;
    margin-top: 4px;
}

.ladder-direction-arrow {
    text-align: center;
    color: #94a3b8;
    margin: -6px 0 6px 0;
    font-size: 0.9rem;
}
</style>

<!-- Organogram Interactive Application Logic -->
<script type="text/javascript">
$(document).ready(function () {
    var organogram = {
        canManage: <?php echo $can_manage ? 'true' : 'false'; ?>,
        nodes: [],
        connectors: [],
        palette: {},
        scale: 0.85,
        panX: 40,
        panY: 30,
        isPanning: false,
        dragStart: { x: 0, y: 0 },
        activeSimulationPath: [],
        saveTimeout: null,

        // High quality portraits mapped by role / tier
        avatars: {
            apex: '<?php echo base_url("assets/images/avatars/exec_secretary.jpg"); ?>',
            director_finance: '<?php echo base_url("assets/images/avatars/director_finance.jpg"); ?>',
            director_sports: '<?php echo base_url("assets/images/avatars/director_sports.jpg"); ?>',
            manager: '<?php echo base_url("assets/images/avatars/manager_female.jpg"); ?>',
            fallback: '<?php echo base_url("assets/images/avatar.jpg"); ?>'
        },

        init: function () {
            this.bindEvents();
            this.loadDiagramData();
        },

        // Fetch placed nodes, connectors, and unplaced palette items
        loadDiagramData: function (callback) {
            var self = this;
            $.ajax({
                url: '<?php echo_uri("organogram/get_diagram_data"); ?>',
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        self.nodes = res.nodes || [];
                        self.connectors = res.connectors || [];
                        self.palette = res.palette || {};
                        self.renderCanvas();
                        self.renderPalette();
                        if (callback) callback();
                    } else {
                        appAlert.error(res.message || "Failed to load organogram data.");
                    }
                },
                error: function () {
                    appAlert.error("Network communication error with organogram server.");
                }
            });
        },

        // Pick photo portrait based on role / tier
        getAvatarForNode: function (node) {
            var roleTitle = (node.role_title || '').toLowerCase();
            var tier = parseInt(node.tier_level);
            var isApex = parseInt(node.is_apex) === 1 || tier === 1;

            if (isApex || roleTitle.indexOf('secretary') !== -1) {
                return this.avatars.apex;
            }
            if (roleTitle.indexOf('finance') !== -1 || roleTitle.indexOf('accountant') !== -1) {
                return this.avatars.director_finance;
            }
            if (roleTitle.indexOf('sports') !== -1 || roleTitle.indexOf('venue') !== -1) {
                return this.avatars.director_sports;
            }
            if (roleTitle.indexOf('procurement') !== -1 || roleTitle.indexOf('manager') !== -1 || roleTitle.indexOf('auditor') !== -1) {
                return this.avatars.manager;
            }
            return this.avatars.director_sports;
        },

        // Render Canvas Nodes with Exact Paper-Style Chip Design
        renderCanvas: function () {
            var $nodesContainer = $('#organogram-nodes');
            $nodesContainer.empty();

            if (this.nodes.length === 0) {
                $('#organogram-empty-state').show();
            } else {
                $('#organogram-empty-state').hide();
            }

            var self = this;
            $.each(this.nodes, function (idx, node) {
                var tier = parseInt(node.tier_level) || 1;
                var isApex = parseInt(node.is_apex) === 1 || tier === 1;
                var avatarUrl = self.getAvatarForNode(node);
                var holderText = node.office_holders || 'Designated Office';
                var deptText = node.department_title || 'NCS Department';

                var levelTag = 'LEVEL A';
                var ribbonClass = 'level-a-ribbon';
                var crescentClass = 'level-a-crescent';
                var titleColorClass = 'title-color-a';
                var chipClass = 'paper-chip-level-a';

                if (tier === 2) {
                    levelTag = 'LEVEL B';
                    ribbonClass = 'level-b-ribbon';
                    crescentClass = 'level-b-crescent';
                    titleColorClass = 'title-color-b';
                    chipClass = 'paper-chip-level-b';
                } else if (tier === 3) {
                    levelTag = 'LEVEL C';
                    ribbonClass = 'level-c-ribbon';
                    crescentClass = 'level-c-crescent';
                    titleColorClass = 'title-color-c';
                    chipClass = 'paper-chip-level-c';
                } else if (tier >= 4) {
                    levelTag = 'LEVEL D';
                    ribbonClass = 'level-d-ribbon';
                    crescentClass = 'level-d-crescent';
                    titleColorClass = 'title-color-d';
                    chipClass = 'paper-chip-level-d';
                }

                var html = '';

                // Level A (Apex): Photo on top + white pill below
                if (isApex) {
                    html = [
                        '<div class="level-a-apex-wrapper" id="node-' + node.id + '" data-id="' + node.id + '" style="left: ' + node.pos_x + 'px; top: ' + node.pos_y + 'px;">',
                        '  <div class="level-a-photo-badge">',
                        '    <img src="' + avatarUrl + '" alt="Executive Apex" />',
                        '  </div>',
                        '  <div class="paper-chip-card paper-chip-level-a">',
                        '    <div class="chip-port port-top"></div>',
                        '    <div class="chip-crescent-arc level-a-crescent"></div>',
                        '    <div class="chip-ribbon-tag level-a-ribbon">' + levelTag + '</div>',
                        '    <div class="chip-content-body text-center" style="margin-left: 20px;">',
                        '      <div class="chip-title ' + titleColorClass + '" title="' + node.role_title + '">' + node.role_title + '</div>',
                        '      <div class="chip-subtitle">' + deptText + '</div>',
                        '      <div class="chip-holder-name">' + holderText + '</div>',
                        '    </div>',
                        '    <div class="chip-hover-actions">',
                        '      <button type="button" class="chip-mini-btn btn-trace" data-id="' + node.id + '" data-role="' + node.role_id + '" title="Simulate Approval Workflow"><i data-feather="play" class="icon-12"></i></button>',
                        (self.canManage ? '      <button type="button" class="chip-mini-btn btn-del btn-remove" data-id="' + node.id + '" title="Remove (restore to palette)"><i data-feather="x" class="icon-12"></i></button>' : ''),
                        '    </div>',
                        '    <div class="chip-port port-bottom"></div>',
                        '  </div>',
                        '</div>'
                    ].join('');
                } else {
                    // Level B, C, D: Paper capsule chip with left crescent arc & circular photo
                    html = [
                        '<div class="paper-chip-card ' + chipClass + '" id="node-' + node.id + '" data-id="' + node.id + '" style="left: ' + node.pos_x + 'px; top: ' + node.pos_y + 'px;">',
                        '  <div class="chip-port port-top"></div>',
                        '  <div class="chip-crescent-arc ' + crescentClass + '"></div>',
                        '  <div class="chip-ribbon-tag ' + ribbonClass + '">' + levelTag + '</div>',
                        '  <div class="chip-avatar-wrapper">',
                        '    <img class="chip-avatar-img" src="' + avatarUrl + '" alt="' + node.role_title + '" />',
                        '  </div>',
                        '  <div class="chip-content-body">',
                        '    <div class="chip-title ' + titleColorClass + '" title="' + node.role_title + '">' + node.role_title + '</div>',
                        '    <div class="chip-subtitle">' + deptText + '</div>',
                        '    <div class="chip-holder-name">' + holderText + '</div>',
                        '  </div>',
                        '  <div class="chip-hover-actions">',
                        '    <button type="button" class="chip-mini-btn btn-trace" data-id="' + node.id + '" data-role="' + node.role_id + '" title="Simulate Approval Workflow"><i data-feather="play" class="icon-12"></i></button>',
                        (self.canManage ? '    <button type="button" class="chip-mini-btn btn-del btn-remove" data-id="' + node.id + '" title="Remove (restore to palette)"><i data-feather="x" class="icon-12"></i></button>' : ''),
                        '  </div>',
                        '  <div class="chip-port port-bottom"></div>',
                        '</div>'
                    ].join('');
                }

                $nodesContainer.append(html);
            });

            feather.replace();
            this.renderConnectors();
            this.initNodeDraggables();
        },

        // Render Orthogonal Stepped Connectors matching infographic reference
        renderConnectors: function () {
            var $group = $('#connectors-group');
            $group.empty();

            var self = this;
            var nodeMap = {};
            $.each(this.nodes, function (i, n) {
                nodeMap[n.id] = n;
            });

            // Group children by parent for clean dual-branch orthogonal crossbars
            var parentChildrenMap = {};
            $.each(this.connectors, function (i, conn) {
                var pId = conn.to_node_id;   // superior (parent)
                var cId = conn.from_node_id; // subordinate (child)

                if (!nodeMap[pId] || !nodeMap[cId]) return;

                if (!parentChildrenMap[pId]) {
                    parentChildrenMap[pId] = [];
                }
                parentChildrenMap[pId].push({
                    childId: cId,
                    connId: conn.id
                });
            });

            // Dimensions for port calculations
            var getDimensions = function (node) {
                var tier = parseInt(node.tier_level) || 1;
                var isApex = parseInt(node.is_apex) === 1 || tier === 1;
                if (isApex) return { w: 270, h: 120 }; // wrapper height
                if (tier === 2) return { w: 280, h: 68 };
                if (tier === 3) return { w: 260, h: 62 };
                return { w: 230, h: 56 };
            };

            // Draw orthogonal stepped tree branches for each parent and its children
            $.each(parentChildrenMap, function (pId, children) {
                var parentNode = nodeMap[pId];
                if (!parentNode) return;

                var pDim = getDimensions(parentNode);
                var pX = parseFloat(parentNode.pos_x) + (pDim.w / 2);
                var pY = parseFloat(parentNode.pos_y) + pDim.h; // bottom of parent

                // Calculate average or midpoint Y between parent and children
                var childTopYList = [];
                $.each(children, function (j, item) {
                    var cNode = nodeMap[item.childId];
                    if (cNode) {
                        childTopYList.push(parseFloat(cNode.pos_y));
                    }
                });

                if (childTopYList.length === 0) return;

                var avgChildY = childTopYList.reduce(function(a, b){ return a + b; }, 0) / childTopYList.length;
                var busY = Math.round(pY + ((avgChildY - pY) * 0.48));

                // Trunk line from parent down to busY
                var minChildX = pX;
                var maxChildX = pX;

                $.each(children, function (j, item) {
                    var cNode = nodeMap[item.childId];
                    var cDim = getDimensions(cNode);
                    var cX = parseFloat(cNode.pos_x) + (cDim.w / 2);
                    var cY = parseFloat(cNode.pos_y);

                    if (cX < minChildX) minChildX = cX;
                    if (cX > maxChildX) maxChildX = cX;

                    // Stepped path: Child (cX, cY) -> up to (cX, busY) -> across to (pX, busY) -> up to (pX, pY)
                    var pathD = 'M ' + cX + ' ' + cY + ' L ' + cX + ' ' + busY + ' L ' + pX + ' ' + busY + ' L ' + pX + ' ' + pY;

                    var isPathActive = self.activeSimulationPath.indexOf(parseInt(item.childId)) !== -1 && 
                                       self.activeSimulationPath.indexOf(parseInt(pId)) !== -1;

                    var pathEl = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    pathEl.setAttribute('d', pathD);
                    pathEl.setAttribute('class', 'workflow-connector-path' + (isPathActive ? ' active-path' : ''));
                    pathEl.setAttribute('marker-end', isPathActive ? 'url(#arrow-active)' : 'url(#arrow-standard)');
                    pathEl.setAttribute('id', 'conn-' + item.connId);
                    $group.append(pathEl);
                });
            });
        },

        // Render Right-Side Palette with unplaced roles grouped by branch (Zero Duplication!)
        renderPalette: function () {
            var $container = $('#palette-departments-container');
            $container.empty();

            var totalUnplaced = 0;
            var self = this;

            $.each(this.palette, function (deptId, item) {
                var dept = item.department;
                var roles = item.roles || [];
                totalUnplaced += roles.length;

                if (roles.length === 0) return;

                var rolesHtml = '';
                $.each(roles, function (rIdx, role) {
                    var holders = parseInt(role.holders_count) || 0;
                    rolesHtml += [
                        '<div class="palette-role-item" draggable="true" data-role-id="' + role.id + '" data-dept-id="' + dept.id + '">',
                        '  <div class="role-info-left">',
                        '    <i data-feather="grid" class="icon-12 role-drag-handle"></i>',
                        '    <span class="role-title-text" title="' + role.title + '">' + role.title + '</span>',
                        '  </div>',
                        '  <span class="badge bg-light text-muted" style="font-size:0.65rem;">' + holders + ' staff</span>',
                        '</div>'
                    ].join('');
                });

                var deptCard = [
                    '<div class="palette-dept-card" id="palette-dept-' + dept.id + '">',
                    '  <div class="palette-dept-header">',
                    '    <div class="d-flex align-items-center gap-2">',
                    '      <span class="badge bg-secondary" style="font-size:0.65rem;">' + (dept.code || 'DEPT') + '</span>',
                    '      <strong style="font-size:0.8rem; color:#0f172a;" title="' + dept.title + '">' + dept.title + '</strong>',
                    '    </div>',
                    '    <div>',
                    (self.canManage ? 
                    '      <button type="button" class="btn btn-outline-primary btn-xs btn-place-branch" data-dept-id="' + dept.id + '" title="Place branch on canvas">' +
                    '        + Branch (' + roles.length + ')' +
                    '      </button>' : ''),
                    '    </div>',
                    '  </div>',
                    '  <div class="p10">',
                    rolesHtml,
                    '  </div>',
                    '</div>'
                ].join('');

                $container.append(deptCard);
            });

            $('#unplaced-counter').text(totalUnplaced);

            if (totalUnplaced === 0) {
                $container.html('<div class="p20 text-center text-muted"><i data-feather="check-circle" class="icon-24 text-success mb10"></i><p class="mb0 font-bold">All Offices Placed</p><small>All departmental roles exist in organogram.</small></div>');
            }

            feather.replace();
            this.initPaletteDraggables();
        },

        // HTML5 Drag and Drop from Palette to Canvas
        initPaletteDraggables: function () {
            var self = this;
            $('.palette-role-item').on('dragstart', function (e) {
                var roleId = $(this).data('role-id');
                var deptId = $(this).data('dept-id');
                e.originalEvent.dataTransfer.setData('text/plain', JSON.stringify({
                    type: 'role',
                    role_id: roleId,
                    dept_id: deptId
                }));
                $(this).addClass('is-dragging');
            });

            $('.palette-role-item').on('dragend', function () {
                $(this).removeClass('is-dragging');
            });
        },

        // Canvas node drag repositioning with real-time orthogonal line recalculation & auto-save
        initNodeDraggables: function () {
            var self = this;
            var activeDragNode = null;
            var startMouseX = 0;
            var startMouseY = 0;
            var startNodeX = 0;
            var startNodeY = 0;

            $('.paper-chip-card, .level-a-apex-wrapper').off('mousedown').on('mousedown', function (e) {
                if ($(e.target).closest('.chip-mini-btn').length > 0) return;
                e.stopPropagation();

                activeDragNode = $(this);
                activeDragNode.addClass('is-dragging');
                startMouseX = e.clientX;
                startMouseY = e.clientY;
                startNodeX = parseFloat(activeDragNode.css('left')) || 0;
                startNodeY = parseFloat(activeDragNode.css('top')) || 0;

                $(document).on('mousemove.nodeDrag', function (ev) {
                    if (!activeDragNode) return;
                    var dx = (ev.clientX - startMouseX) / self.scale;
                    var dy = (ev.clientY - startMouseY) / self.scale;
                    var newX = Math.round(startNodeX + dx);
                    var newY = Math.round(startNodeY + dy);

                    activeDragNode.css({ left: newX + 'px', top: newY + 'px' });

                    // Live connector update during drag
                    var nodeId = parseInt(activeDragNode.data('id'));
                    $.each(self.nodes, function (i, n) {
                        if (n.id === nodeId) {
                            n.pos_x = newX;
                            n.pos_y = newY;
                        }
                    });
                    self.renderConnectors();
                });

                $(document).on('mouseup.nodeDrag', function (ev) {
                    if (!activeDragNode) return;
                    $(document).off('mousemove.nodeDrag mouseup.nodeDrag');
                    activeDragNode.removeClass('is-dragging');

                    var nodeId = parseInt(activeDragNode.data('id'));
                    var finalX = parseFloat(activeDragNode.css('left'));
                    var finalY = parseFloat(activeDragNode.css('top'));

                    activeDragNode = null;

                    // Automatically persist node position to backend (Auto-save)
                    if (self.canManage) {
                        self.triggerAutoSave(nodeId, finalX, finalY);
                    }
                });
            });
        },

        // Debounced Auto-Save
        triggerAutoSave: function (nodeId, posX, posY) {
            var self = this;
            $('#autosave-status').html('<span class="spinner-border spinner-border-sm text-primary mr5"></span> Saving...');

            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(function () {
                $.ajax({
                    url: '<?php echo_uri("organogram/save_node_position"); ?>',
                    type: 'POST',
                    data: { id: nodeId, pos_x: posX, pos_y: posY },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            $('#autosave-status').html('<i data-feather="check" class="icon-12 text-success mr5"></i> <span>Auto-saved</span>');
                            feather.replace();
                        } else {
                            $('#autosave-status').html('<span class="text-danger">Save failed</span>');
                        }
                    }
                });
            }, 300);
        },

        // Bind interactive event handlers
        bindEvents: function () {
            var self = this;
            var $container = $('#organogram-container');

            // Pan Canvas
            $container.on('mousedown', function (e) {
                if ($(e.target).closest('.paper-chip-card, .level-a-apex-wrapper').length > 0) return;
                self.isPanning = true;
                self.dragStart.x = e.clientX - self.panX;
                self.dragStart.y = e.clientY - self.panY;
            });

            $(document).on('mousemove', function (e) {
                if (!self.isPanning) return;
                self.panX = e.clientX - self.dragStart.x;
                self.panY = e.clientY - self.dragStart.y;
                self.applyTransform();
            });

            $(document).on('mouseup', function () {
                self.isPanning = false;
            });

            // Zoom via Mouse Wheel
            $container.on('wheel', function (e) {
                e.preventDefault();
                var delta = e.originalEvent.deltaY;
                var zoomFactor = delta < 0 ? 1.08 : 0.92;
                self.setZoom(self.scale * zoomFactor);
            });

            // Zoom Toolbar Buttons
            $('#btn-zoom-in').on('click', function () { self.setZoom(self.scale * 1.15); });
            $('#btn-zoom-out').on('click', function () { self.setZoom(self.scale * 0.85); });
            $('#btn-zoom-reset').on('click', function () {
                self.scale = 0.85;
                self.panX = 40;
                self.panY = 30;
                self.applyTransform();
            });
            $('#btn-fit-canvas').on('click', function () { self.fitToCanvas(); });

            // HTML5 Drag & Drop Over Canvas
            $container.on('dragover', function (e) {
                e.preventDefault();
                e.originalEvent.dataTransfer.dropEffect = 'move';
            });

            $container.on('drop', function (e) {
                e.preventDefault();
                var rawData = e.originalEvent.dataTransfer.getData('text/plain');
                if (!rawData) return;

                var data = JSON.parse(rawData);
                if (data.type === 'role') {
                    var rect = $container[0].getBoundingClientRect();
                    var screenX = e.clientX - rect.left;
                    var screenY = e.clientY - rect.top;

                    var worldX = Math.round((screenX - self.panX) / self.scale);
                    var worldY = Math.round((screenY - self.panY) / self.scale);

                    self.dropRoleOnCanvas(data.role_id, worldX, worldY);
                }
            });

            // Place entire branch button
            $(document).on('click', '.btn-place-branch', function () {
                var deptId = $(this).data('dept-id');
                self.placeDepartment(deptId);
            });

            // Remove node from canvas (Restores to palette!)
            $(document).on('click', '.btn-remove', function () {
                var nodeId = $(this).data('id');
                self.removeNode(nodeId);
            });

            // Trace approval flow from node
            $(document).on('click', '.btn-trace', function () {
                var roleId = $(this).data('role');
                var nodeId = $(this).data('id');
                self.openSimulator(roleId, nodeId);
            });

            // Auto Layout
            $('#btn-auto-layout').on('click', function () {
                self.autoLayout();
            });

            // Reset Hierarchy to Dual Branching
            $('#btn-reset-hierarchy').on('click', function () {
                if (confirm("Reset organogram to standard NCS dual-branching hierarchy? All 15 standard dual-branching offices and connectors will be aligned.")) {
                    self.resetHierarchy();
                }
            });

            // Open Simulator Modal
            $('#btn-open-simulator').on('click', function () {
                self.openSimulator();
            });

            // Simulator Form Submission
            $('#workflow-sim-form').on('submit', function (e) {
                e.preventDefault();
                self.runSimulation();
            });

            // Search filter in right palette
            $('#palette-search').on('keyup', function () {
                var q = $(this).val().toLowerCase();
                $('.palette-dept-card').each(function () {
                    var $card = $(this);
                    var matchCount = 0;
                    $card.find('.palette-role-item').each(function () {
                        var text = $(this).find('.role-title-text').text().toLowerCase();
                        if (text.indexOf(q) !== -1 || q === '') {
                            $(this).show();
                            matchCount++;
                        } else {
                            $(this).hide();
                        }
                    });

                    if (matchCount > 0 || q === '') {
                        $card.show();
                    } else {
                        $card.hide();
                    }
                });
            });

            // Mobile toggle palette
            $('#btn-toggle-palette').on('click', function () {
                $('#organogram-palette').toggleClass('d-none');
            });
        },

        // Drop Role on Canvas (AJAX add_node + remove from palette + auto-save)
        dropRoleOnCanvas: function (roleId, posX, posY) {
            var self = this;
            $.ajax({
                url: '<?php echo_uri("organogram/add_node"); ?>',
                type: 'POST',
                data: { role_id: roleId, pos_x: posX, pos_y: posY },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        appAlert.success(res.message);
                        self.loadDiagramData();
                    } else {
                        appAlert.error(res.message || "Failed to place role.");
                    }
                }
            });
        },

        // Place entire Department Branch
        placeDepartment: function (deptId) {
            var self = this;
            $.ajax({
                url: '<?php echo_uri("organogram/add_department"); ?>',
                type: 'POST',
                data: { department_id: deptId, pos_x: 300, pos_y: 350 },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        appAlert.success(res.message);
                        self.loadDiagramData();
                    } else {
                        appAlert.error(res.message);
                    }
                }
            });
        },

        // Remove node from canvas (Restores to palette)
        removeNode: function (nodeId) {
            var self = this;
            $.ajax({
                url: '<?php echo_uri("organogram/remove_node"); ?>',
                type: 'POST',
                data: { id: nodeId },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        appAlert.success(res.message);
                        self.loadDiagramData();
                    } else {
                        appAlert.error(res.message);
                    }
                }
            });
        },

        // Auto Layout
        autoLayout: function () {
            var self = this;
            $.ajax({
                url: '<?php echo_uri("organogram/auto_layout"); ?>',
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        appAlert.success("Dual-branching hierarchy re-aligned and saved.");
                        self.loadDiagramData(function () {
                            self.fitToCanvas();
                        });
                    } else {
                        appAlert.error(res.message);
                    }
                }
            });
        },

        // Reset Hierarchy
        resetHierarchy: function () {
            var self = this;
            $.ajax({
                url: '<?php echo_uri("organogram/reset_hierarchy"); ?>',
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        appAlert.success(res.message);
                        self.loadDiagramData(function () {
                            self.fitToCanvas();
                        });
                    } else {
                        appAlert.error(res.message);
                    }
                }
            });
        },

        // Apply scale & pan transform
        applyTransform: function () {
            var transform = 'translate(' + this.panX + 'px, ' + this.panY + 'px) scale(' + this.scale + ')';
            $('#organogram-world').css({ transform: transform });
            $('#zoom-level-text').text(Math.round(this.scale * 100) + '%');
        },

        // Set zoom with boundary clamping
        setZoom: function (newScale) {
            this.scale = Math.min(Math.max(newScale, 0.3), 2.0);
            this.applyTransform();
        },

        // Fit all nodes to canvas
        fitToCanvas: function () {
            if (this.nodes.length === 0) return;
            var minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
            $.each(this.nodes, function (i, n) {
                var x = parseFloat(n.pos_x);
                var y = parseFloat(n.pos_y);
                if (x < minX) minX = x;
                if (y < minY) minY = y;
                if (x + 280 > maxX) maxX = x + 280;
                if (y + 120 > maxY) maxY = y + 120;
            });

            var $container = $('#organogram-container');
            var contW = $container.width() - 80;
            var contH = $container.height() - 80;
            var treeW = maxX - minX;
            var treeH = maxY - minY;

            var scaleX = contW / treeW;
            var scaleY = contH / treeH;
            this.scale = Math.min(Math.min(scaleX, scaleY), 1.0);
            this.panX = 40 - (minX * this.scale);
            this.panY = 30 - (minY * this.scale);
            this.applyTransform();
        },

        // Open Workflow Simulator Modal
        openSimulator: function (preferredRoleId, preferredNodeId) {
            var $select = $('#sim-role-select');
            $select.empty();
            $select.append('<option value="">-- Choose initiating office --</option>');

            $.each(this.nodes, function (i, n) {
                var selected = (preferredNodeId && n.id == preferredNodeId) || (preferredRoleId && n.role_id == preferredRoleId) ? 'selected' : '';
                $select.append('<option value="' + n.role_id + '" data-node-id="' + n.id + '" ' + selected + '>' + n.role_title + ' (' + (n.department_code || n.department_title) + ')</option>');
            });

            $('#workflow-simulator-modal').modal('show');

            if (preferredRoleId || preferredNodeId) {
                this.runSimulation();
            }
        },

        // Run Bottom-to-Top Approval Simulation
        runSimulation: function () {
            var roleId = $('#sim-role-select').val();
            var nodeId = $('#sim-role-select option:selected').data('node-id');
            var amount = $('#sim-amount').val() || 0;
            var docType = $('#sim-doc-type').val();
            var self = this;

            if (!roleId && !nodeId) {
                appAlert.error("Please select an initiating office.");
                return;
            }

            $.ajax({
                url: '<?php echo_uri("organogram/simulate_workflow"); ?>',
                type: 'POST',
                data: {
                    role_id: roleId,
                    node_id: nodeId,
                    amount_ugx: amount,
                    document_type: docType
                },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        self.renderSimulationResults(res);
                    } else {
                        appAlert.error(res.message || "Approval simulation failed.");
                    }
                }
            });
        },

        // Render Simulation Ladder Results
        renderSimulationResults: function (data) {
            var $container = $('#sim-ladder-steps');
            $container.empty();

            var chain = data.chain || [];
            $('#sim-total-steps-badge').text(chain.length + ' Sequential Steps');

            var pathNodeIds = [];

            $.each(chain, function (idx, step) {
                pathNodeIds.push(step.node_id);

                var actionClass = 'action-review';
                var markerClass = '';
                if (step.action === 'Initiate Requisition') {
                    actionClass = 'action-initiate';
                    markerClass = 'step-initiator';
                } else if (step.action === 'Final Executive Approval') {
                    actionClass = 'action-final';
                    markerClass = 'step-apex';
                } else if (step.action === 'Departmental Head Approval') {
                    actionClass = 'action-approve';
                }

                var stepCard = [
                    '<div class="ladder-step-card">',
                    '  <div class="ladder-step-marker ' + markerClass + '">' + step.step_number + '</div>',
                    '  <div class="ladder-card-header">',
                    '    <div class="ladder-step-title">' + step.role_title + ' <span class="badge bg-light text-dark ml5">' + step.department_title + '</span></div>',
                    '    <span class="ladder-step-action ' + actionClass + '">' + step.action + '</span>',
                    '  </div>',
                    '  <div class="ladder-step-details">',
                    '    <div><i data-feather="user" class="icon-12 mr5"></i><strong>Designation Holder:</strong> ' + step.assigned_users + '</div>',
                    '    <div><i data-feather="shield" class="icon-12 mr5"></i><strong>Mandate:</strong> ' + step.approval_label + '</div>',
                    '  </div>',
                    '</div>'
                ].join('');

                $container.append(stepCard);

                if (idx < chain.length - 1) {
                    $container.append('<div class="ladder-direction-arrow"><i data-feather="arrow-down" class="icon-14"></i></div>');
                }
            });

            $('#sim-results-container').slideDown(200);
            $('#btn-highlight-on-canvas').show();
            feather.replace();

            // Set active simulation path on canvas
            this.activeSimulationPath = pathNodeIds;
            this.highlightPathOnCanvas(pathNodeIds);

            $('#btn-highlight-on-canvas').off('click').on('click', function () {
                $('#workflow-simulator-modal').modal('hide');
            });
        },

        // Highlight Active Approval Path on Canvas
        highlightPathOnCanvas: function (nodeIds) {
            $('.paper-chip-card, .level-a-apex-wrapper').removeClass('active-highlight');
            $.each(nodeIds, function (i, id) {
                $('#node-' + id).addClass('active-highlight');
                $('#node-' + id).find('.paper-chip-card').addClass('active-highlight');
            });
            this.renderConnectors();
        }
    };

    // Initialize Organogram
    organogram.init();
});
</script>
