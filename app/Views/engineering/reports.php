<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="bar-chart-2" class="icon-16 mr5"></i> Engineering Department Statutory Analytics & Condition Reports</h4>
        </div>
        <div class="card-body p30">
            <!-- TOP KPI STATS -->
            <div class="row mb30">
                <div class="col-md-2">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-primary fw-bold mb5"><?php echo $total_work_orders; ?></h2>
                        <span class="text-muted">Total Work Orders</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-warning fw-bold mb5"><?php echo $pending_work_orders; ?></h2>
                        <span class="text-muted">Pending Repairs</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-info fw-bold mb5"><?php echo $total_assets; ?></h2>
                        <span class="text-muted">Infra Assets</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-success fw-bold mb5"><?php echo $total_civil_assets; ?></h2>
                        <span class="text-muted">Civil / Land Parcels</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-dark fw-bold mb5"><?php echo $total_electrical_assets; ?></h2>
                        <span class="text-muted">Generators & Plant</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-primary fw-bold mb5"><?php echo $total_technicians; ?></h2>
                        <span class="text-muted">Field Technicians</span>
                    </div>
                </div>
            </div>

            <!-- CIVIL & LAND CADASTRAL REGISTER SUMMARY -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="map" class="icon-16"></i> Civil Infrastructure & Cadastral Land Summary</h5>
            <div class="table-responsive mb30">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Property Code</th>
                            <th>Asset Name & Deed No</th>
                            <th>Parcel Location</th>
                            <th>Acreage</th>
                            <th class="text-right text-end">Valuation (UGX)</th>
                            <th>Boundary Status</th>
                            <th>Encroachment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($civil_assets)): ?>
                            <?php foreach ($civil_assets as $civ): 
                                $code = $civ->asset_number ?? ($civ->property_code ?? 'CIV-NCS');
                                $name = $civ->asset_description ?? ($civ->asset_name ?? 'Civil Asset');
                                $deed = $civ->tag_number ?? ($civ->title_deed_no ?? 'N/A');
                                $loc  = $civ->category_segment3 ?? ($civ->parcel_location ?? 'Lugogo Complex');
                                $val  = $civ->adjusted_cost ?? ($civ->valuation_ugx ?? 0);
                                $bnd  = $civ->verification_status ?? ($civ->boundary_status ?? 'VERIFIED');
                                $enc  = $civ->encroachment_status ?? 'CLEAR';
                                $acr  = isset($civ->useful_life_years) ? ($civ->useful_life_years > 0 ? $civ->useful_life_years . ' Yrs' : 'Cadastral Plot') : (isset($civ->acreage_ha) ? $civ->acreage_ha . ' Ha' : 'N/A');
                            ?>
                                <tr>
                                    <td><strong><?php echo $code; ?></strong></td>
                                    <td><strong><?php echo $name; ?></strong><br><small class="text-muted">Deed: <?php echo $deed; ?></small></td>
                                    <td><?php echo $loc; ?></td>
                                    <td><?php echo $acr; ?></td>
                                    <td class="text-right text-end"><?php echo to_currency($val); ?></td>
                                    <td><span class="badge bg-success"><?php echo $bnd; ?></span></td>
                                    <td><span class="badge bg-success"><?php echo $enc; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted">No civil land parcels registered.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- ELECTRICAL MACHINERY & GENERATORS TELEMETRY -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="zap" class="icon-16"></i> Electrical Machinery & Generator Power Readiness</h5>
            <div class="table-responsive mb30">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Equipment Code</th>
                            <th>Equipment Name</th>
                            <th>Location</th>
                            <th>Fuel Level</th>
                            <th>Runtime Hours</th>
                            <th>ATS Auto-Start</th>
                            <th>Last Service</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($electrical_assets)): ?>
                            <?php foreach ($electrical_assets as $ele): 
                                $e_code = $ele->asset_number ?? ($ele->equipment_code ?? 'ELE-NCS');
                                $e_name = $ele->asset_description ?? ($ele->equipment_name ?? 'Electrical Asset');
                                $e_rate = $ele->tag_number ?? ($ele->rating_kva_or_kw ?? '60 KVA');
                                $e_loc  = $ele->category_segment3 ?? ($ele->facility_location ?? 'Lugogo Complex');
                                $e_fuel = isset($ele->fuel_level_percent) ? floatval($ele->fuel_level_percent) : 100;
                                $e_cap  = isset($ele->fuel_tank_capacity_l) ? floatval($ele->fuel_tank_capacity_l) : 200;
                                $e_hrs  = isset($ele->running_hours) ? floatval($ele->running_hours) : (isset($ele->runtime_hours) ? floatval($ele->runtime_hours) : 0);
                                $e_ats  = $ele->verification_status ?? ($ele->ats_status ?? 'VERIFIED');
                                $e_dt   = $ele->last_service_date ?? substr($ele->created_at, 0, 10);
                            ?>
                                <tr>
                                    <td><strong><?php echo $e_code; ?></strong></td>
                                    <td><strong><?php echo $e_name; ?></strong><br><small class="text-muted"><?php echo $e_rate; ?></small></td>
                                    <td><?php echo $e_loc; ?></td>
                                    <td><?php echo $e_cap > 0 ? ($e_cap . ' L (' . $e_fuel . '%)') : 'N/A'; ?></td>
                                    <td><?php echo $e_hrs; ?> Hrs</td>
                                    <td><span class="badge bg-success"><?php echo $e_ats; ?></span></td>
                                    <td><?php echo format_to_date($e_dt); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted">No electrical assets registered.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- ASSET CONDITION RATING BREAKDOWN -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="hard-drive" class="icon-16"></i> Facility & Stadium Infrastructure Asset Register</h5>
            <div class="table-responsive mb30">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Asset Code</th>
                            <th>Asset Name</th>
                            <th>Category</th>
                            <th>Facility Venue</th>
                            <th>Condition Rating</th>
                            <th class="text-right text-end">Valued Price (UGX)</th>
                            <th>Next Maintenance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($assets)): ?>
                            <?php foreach ($assets as $ast): ?>
                                <tr>
                                    <td><strong><?php echo $ast->asset_code; ?></strong></td>
                                    <td><?php echo $ast->asset_name; ?></td>
                                    <td><?php echo $ast->category; ?></td>
                                    <td><?php echo $ast->facility_location; ?></td>
                                    <td><span class="badge bg-success"><?php echo $ast->condition_rating; ?></span></td>
                                    <td class="text-right text-end"><?php echo to_currency($ast->purchase_value); ?></td>
                                    <td><?php echo format_to_date($ast->next_maintenance_date); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted">No infrastructure asset records registered.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- FACILITY READINESS INSPECTIONS -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="check-square" class="icon-16"></i> Event Readiness & Stadium Safety Certification</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Inspection Code</th>
                            <th>Event / Facility Venue</th>
                            <th>Inspection Date</th>
                            <th>Civil Safety</th>
                            <th>Electrical Safety</th>
                            <th class="text-center">Readiness Score</th>
                            <th>Certification Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($inspections)): ?>
                            <?php foreach ($inspections as $insp): ?>
                                <tr>
                                    <td><strong><?php echo $insp->inspection_code; ?></strong></td>
                                    <td><?php echo $insp->event_or_facility; ?></td>
                                    <td><?php echo format_to_date($insp->inspection_date); ?></td>
                                    <td><span class="badge bg-info"><?php echo $insp->civil_safety_status; ?></span></td>
                                    <td><span class="badge bg-info"><?php echo $insp->electrical_safety_status; ?></span></td>
                                    <td class="text-center"><strong class="text-primary"><?php echo $insp->readiness_score; ?>%</strong></td>
                                    <td><span class="badge bg-success"><?php echo $insp->status; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted">No facility inspection certificates recorded.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
