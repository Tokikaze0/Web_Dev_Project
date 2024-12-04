<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!-- <h1>Welcome to <?php echo htmlspecialchars($_settings->info('name'), ENT_QUOTES, 'UTF-8'); ?></h1> -->
<hr>
<section class="content">
    <div class="row">
        <?php
        $stmt = $conn->query("
            SELECT 
                COUNT(*) AS total_events,
                SUM(unix_timestamp(datetime_end) <= unix_timestamp(now())) AS finished_events,
                SUM(unix_timestamp(now()) BETWEEN unix_timestamp(datetime_start) AND unix_timestamp(datetime_end)) AS ongoing_events
            FROM event_list
        ");
        $result = $stmt->fetch_assoc();

        $total_events = $result['total_events'];
        $finished_events = $result['finished_events'];
        $ongoing_events = $result['ongoing_events'];
        $listed_audience = $conn->query("SELECT COUNT(*) FROM event_audience")->fetch_row()[0];
        ?>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-th-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Events</span>
                    <span class="info-box-number"><?php echo $total_events; ?></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Listed Audience</span>
                    <span class="info-box-number"><?php echo $listed_audience; ?></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-th-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Finished Events</span>
                    <span class="info-box-number"><?php echo $finished_events; ?></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-th-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">On-Going Events</span>
                    <span class="info-box-number"><?php echo $ongoing_events; ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>