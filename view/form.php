<form method="post" class="form-inline">
    <div class="row">
        <div class="form-group">
            <label for="criteria">Show the</label>
            <select id="criteria" name="criteria" class="form-control" >
                <?php foreach( $criteria_list as $value => $label ) { ?>
                    <option value="<?= $value ?>" <?= ( ( $value == $criteria ) ? 'selected' : '' ) ?>><?= $label ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="radius">cities within</label>
            <input type="text" class="form-control " id="radius" name="radius" value="<?= $radius ?>">
            <label for="radius">miles of</label>
        </div>
    </div><!-- row -->
    <div class="row">
        <div class="form-group">
            <label for="lat">lat:</label>
            <input type="text" class="form-control " id="lat" name="lat" value="<?= $lat ?>">
        </div>
        <div class="form-group">
            <label for="lat">lon:</label>
            <input type="text" class="form-control " id="lon" name="lon" value="<?= $lon ?>">
        </div>
    </div><!-- row -->
    <div class="row">
        <div class="form-group">
            <label for="view_type">Show results as</label>
            <select name="view_type" id="view_type" class="form-control" >
                <?php foreach( $view_list as $value => $label ) { ?>
                    <option value="<?= $value ?>" <?= ( ( $value == $view_type ) ? 'selected' : '' ) ?>><?= $label ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">Search</button>
    </div><!-- row -->
</form>