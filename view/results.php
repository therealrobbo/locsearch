<h3>Showing <?= $criteria_label ?> cities within <?= $radius ?> miles of lat: <?= $lat ?>, lon: <?= $lon ?></h3>

<?php
if ( !empty( $error_code ) ) { ?>
    <div class="alert alert-danger"><?= $error_message ?></div>
<?php } else { ?>

<table class="table">
    <thead>
        <tr>
            <th>City</th>
            <th>State</th>
            <th>Population</th>
            <th>lat</th>
            <th>lon</th>
            <th>Distance</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $result_count = 0;
    foreach( $results as $result_rec ) { ?>
        <tr>
            <td><?= $result_rec['city'] ?></td>
            <td><?= $result_rec['state'] ?></td>
            <td><?= $result_rec['population'] ?></td>
            <td><?= $result_rec['lat'] ?></td>
            <td><?= $result_rec['lon'] ?></td>
            <td><?= number_format( $result_rec['distance'], 1 ) ?> miles</td>
        </tr>
    <?php
        $result_count++;
        if ( $result_count == $results_max ) {
            break;
        }
    } ?>
    </tbody>
</table>
<?php } ?>
<a class="btn btn-default" href="<?= $base_url ?>">Search Again</a>
