<div class="pcg_mainitem">
    <label>Main Price: </label>
    <input type="text" placeholder="Price" name="pcbuilder_mprice" value="<?= $pcbuilder_mprice ?>">
</div><!-- pcg_item -->

<div class="pcbuilder_gen pcbuilder_pgen">
    <button type="button" data-name="pcbuilder_price" class="button button-primary button-large">Add New Price</button>

    <div class="pcbuilder_genco" id="sortable">
        <?php if(!empty($data)){ ?>
        <?php $i=0; foreach($data as $k=>$d){ ?>
            <div>
        <div class="pcg_item">
            <i>&times;</i>
            <select name="pcbuilder_price[<?= $i ?>][country]">
                <?php foreach($countries as $country){ ?>
                <option <?= ($country == sanitize_text_field($k)) ? 'selected' : '' ?> value="<?= $country ?>"><?= $country ?></option>
                <?php } ?>
            </select>
            <input type="text" placeholder="Price" name="pcbuilder_price[<?= $i ?>][price]" value="<?= (float)$d ?>">
        </div><!-- pcg_item -->
                </div>
        <?php $i++;}} ?>
    </div><!-- pcbuilder_gen -->

    <div class="pcbuilder_store">
        <div class="pcg_item">
            <i>&times;</i>
            <select>
                <?php foreach($countries as $country){ ?>
                <option <?= ($country == 'Egypt') ? 'selected' : '' ?> value="<?= $country ?>"><?= $country ?></option>
                <?php } ?>
            </select>
            <input type="text" placeholder="Price">
        </div><!-- pcg_item -->
    </div><!-- pcbuilder_store -->
</div><!-- pcbuilder_gen -->

