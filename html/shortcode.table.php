<div class="pcbuilder-table-container">
<a href="#" class="pcbuilder-table-add-btn">Add New Item</a>
<a href="#" class="pcbuilder-budget-add-btn">Build over Budget</a>

<div class="pcbuilder-table-form">
    <div class="pcbuilder-table-formco">
        <div class="pcbuilder-table-form-item">
            <label for="">Component:</label>
            <select class="pcbuilder-component">
                <option selected disabled value="">Components list</option>
                <?php 
                    if(!empty($components)){
                        foreach($components as $component){
                            echo '<option value="'.$component.'">'.$component.'</option>';
                        }
                    }
                ?>
            </select>
        </div><!-- pcbuilder-table-form-item -->
        <div class="pcbuilder-table-form-item">
            <label for="">Products:</label>
            <select class="pcbuilder-product">
                <option selected disabled value="">Products list</option>
            </select>
        </div><!-- pcbuilder-table-form-item -->
        <div class="pcbuilder-table-form-item">
            <button class="pcbuilder-addrow">Add</button>
            <button class="pcbuilder-cancel">Cancel</button>
        </div><!-- pcbuilder-table-form-item -->
    </div><!-- pcbuilder-table-formco -->
</div><!-- pcbuilder-table-form -->

<?php include_once("shortcode.budget.generator.php") ?>

<div class="pcbuilder-table">
    <div class="pcbuilder-table-co">
        <div class="pcbuilder-table-head">
            <div class="pcbuilder-table-item pcbuilder-3">Component</div><!-- pcbuilder-table-item -->
            <div class="pcbuilder-table-item pcbuilder-6">Product</div><!-- pcbuilder-table-item -->
            <div class="pcbuilder-table-item pcbuilder-3">Price</div><!-- pcbuilder-table-item -->
        </div><!-- pcbuilder-table-head -->
        <div class="pcbuilder-table-body">
            
        </div><!-- pcbuilder-table-body -->
        <div class="pcbuilder-table-foot">
            <div class="pcbuilder-table-item pcbuilder-3">
                <em class="pcbuilder-print-btn"></em>
            </div><!-- pcbuilder-table-item -->
            <div class="pcbuilder-table-item pcbuilder-6">-</div><!-- pcbuilder-table-item -->
            <div class="pcbuilder-table-item pcbuilder-3"><span class="pcbuilder-end-price"></span></div><!-- pcbuilder-table-item -->
        </div><!-- pcbuilder-table-foot -->
    </div><!-- pcbuilder-table-co -->
</div><!-- pcbuilder-table -->
</div><!-- pcbuilder-table-form-container -->

