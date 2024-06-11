<?php if ($this->session->flashdata('msg')) { ?>
    <script>
        triggerSweetAlert("<?= $this->session->flashdata('title') ?>", "<?= $this->session->flashdata('msg') ?>", "<?= $this->session->flashdata('status') ?>")
    </script>
<?php } ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= $header ?></h4>
                <form action="<?= site_url() ?>/emv_view" method="POST">
                    <div class="row mt-3">
                        <?php /*
                        <div class="col-md-4">
                            <label for="frm_dt" class="form-label">From Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="frm_dt" name="frm_dt" value="<?= $selected['frm_dt'] ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="to_dt" class="form-label">To Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="to_dt" name="to_dt" value="<?= $selected['to_dt'] ?>">
                        </div>
                        */ ?>
                        <div class="col-md-8">
                            <label for="search_val" class="form-label">Client ID / Loan ID / UMRN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="search_val" name="search_val" placeholder="Enter client id or loan id or UMRN number" value="<?= $selected['search_val'] ?>">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4" id="search" name="search">Search</button>
                        </div>
                    </div>
                </form>
                <?php if(isset($_REQUEST['search'])){ ?> 
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="display expandable-table date-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>TNX Date</th>
                                            <th>Loan ID</th>
                                            <th>Client ID</th>
                                            <th>Client Name</th>
                                            <th>Status</th>
                                            <th>Mandate Verify</th>
                                            <th>Transaction Verify</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                        foreach ($result as $key => $val) { ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= date('d/m/Y', strtotime($val->TPSL_TXN_DATE_TIME)) ?></td>
                                                <td><?= $val->LOAN_ID ?></td>
                                                <td><?= $val->CLIENT_ID ?></td>
                                                <td><?= $val->CUST_NAME ?></td>
                                                <td><?= strtoupper($val->TXN_MSG) ?></td>
                                                <td><a href="<?= site_url() ?>/search_tnx"><i class="mdi mdi-eye"></i></a></td>
                                                <td><i class="mdi mdi-hand-pointing-right"></i></td>
                                            </tr>
                                        <?php $i++; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>