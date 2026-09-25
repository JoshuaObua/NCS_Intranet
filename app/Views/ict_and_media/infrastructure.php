<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="server" class="icon-16 mr5"></i> Systems Infrastructure Health & Backup Command Center</h4>
        </div>
        <div class="card-body p30">
            <!-- TOP INFRASTRUCTURE CARDS -->
            <div class="row mb30">
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-success fw-bold mb5"><?php echo $uptime; ?></h2>
                        <span class="text-muted">Intranet Uptime (Online)</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-primary fw-bold mb5">SUCCESS</h2>
                        <span class="text-muted">PostgreSQL S3 Backup (02:00 AM)</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-warning fw-bold mb5"><?php echo $open_tickets; ?></h2>
                        <span class="text-muted">Open IT Helpdesk Tickets</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-info fw-bold mb5"><?php echo $active_users; ?></h2>
                        <span class="text-muted">Active Intranet Sessions</span>
                    </div>
                </div>
            </div>

            <!-- CORE SYSTEM NODES & SERVICES -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="cpu" class="icon-16"></i> Critical Server Nodes & Services Status</h5>
            <div class="table-responsive mb30">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Node Name</th>
                            <th>IP Address</th>
                            <th>Role / Service</th>
                            <th>CPU / Memory Load</th>
                            <th>Storage Usage</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>NCS-APP-SRV-01</strong></td>
                            <td><code>192.168.14.202</code></td>
                            <td>Nginx + PHP 8.3-FPM Intranet Web Host</td>
                            <td>12.4% CPU / 4.2 GB RAM</td>
                            <td>42.8 GB / 500 GB (8.5%)</td>
                            <td><span class="badge bg-success">ONLINE</span></td>
                        </tr>
                        <tr>
                            <td><strong>NCS-DB-SRV-01</strong></td>
                            <td><code>127.0.0.1:5432</code></td>
                            <td>PostgreSQL 16 Enterprise Database</td>
                            <td>18.1% CPU / 8.6 GB RAM</td>
                            <td>18.4 GB / 1 TB (1.8%)</td>
                            <td><span class="badge bg-success">ONLINE</span></td>
                        </tr>
                        <tr>
                            <td><strong>NCS-CORE-SW-01</strong></td>
                            <td><code>192.168.14.1</code></td>
                            <td>Cisco 9300 Core 48-Port Switch</td>
                            <td>5.2% CPU Load</td>
                            <td>Port Utilization 68%</td>
                            <td><span class="badge bg-success">ONLINE</span></td>
                        </tr>
                        <tr>
                            <td><strong>NCS-CCTV-NVR-01</strong></td>
                            <td><code>192.168.14.250</code></td>
                            <td>Hikvision 32-Ch Security NVR</td>
                            <td>32 Active Streams</td>
                            <td>12.4 TB / 16 TB (77.5%)</td>
                            <td><span class="badge bg-success">RECORDING</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- DISASTER RECOVERY & BACKUP LOGS -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="database" class="icon-16"></i> Database Disaster Recovery & Encrypted S3 Backups</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Backup Job ID</th>
                            <th>Target Database</th>
                            <th>Encrypted Backup Archive</th>
                            <th>Size</th>
                            <th>Executed Timestamp</th>
                            <th>Vault Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>BAK-PG-2026-0918</strong></td>
                            <td><code>ncs_db</code> (PostgreSQL)</td>
                            <td><code>s3://ncs-backups/pg_ncs_db_20260918_0200.sql.gz.enc</code></td>
                            <td>42.8 MB</td>
                            <td>2026-09-18 02:00:00</td>
                            <td><span class="badge bg-success">VERIFIED & ENCRYPTED</span></td>
                        </tr>
                        <tr>
                            <td><strong>BAK-MEDIA-2026-0917</strong></td>
                            <td><code>media_artifacts</code></td>
                            <td><code>s3://ncs-backups/media_sync_20260917_0200.tar.gz</code></td>
                            <td>1.4 GB</td>
                            <td>2026-09-17 02:00:00</td>
                            <td><span class="badge bg-success">VERIFIED & ENCRYPTED</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
