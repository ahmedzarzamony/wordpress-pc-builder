<div class="pcbuilder-budget-form">
    <div class="pcbuilder-budget-formco">
        <div class="pcbuilder-budget-form-item">
            <label for="">Purpose:</label>
            <select class="pcbuilder-purpose">
                <option selected value="">Any</option>
                <?php 
                    if(!empty($purposes)){
                        foreach($purposes as $purpose){
                            echo '<option value="'.$purpose.'">'.$purpose.'</option>';
                        }
                    }
                ?>
            </select>
        </div><!-- pcbuilder-budget-form-item -->
        <div class="pcbuilder-budget-form-item">
            <label for="">CPU:</label>
            <select class="pcbuilder-cpu">
                <option selected value="">Any</option>
                <?php 
                    if(!empty($brands)){
                        if(isset($brands['cpu'])){
                            foreach($brands['cpu'] as $cpu){
                                echo '<option value="'.$cpu.'">'.$cpu.'</option>';
                            }
                        }
                    }
                ?>
            </select>
        </div><!-- pcbuilder-budget-form-item -->

        <div class="pcbuilder-budget-form-item">
            <label for="">GPU:</label>
            <select class="pcbuilder-gpu">
                <option selected value="">Any</option>
                <?php 
                    if(!empty($brands)){
                        if(isset($brands['gpu'])){
                            foreach($brands['gpu'] as $gpu){
                                echo '<option value="'.$gpu.'">'.$gpu.'</option>';
                            }
                        }
                    }
                ?>
            </select>
        </div><!-- pcbuilder-budget-form-item -->

        <div class="pcbuilder-budget-form-item">
            <div class="pc-builder-budget">
                <input tyupe="text" readonly class="pcbuilder-slider-val" />
                <input type="range" min="400" max="5000" value="600" data-rangeslider>
            </div>
        </div><!-- pcbuilder-budget-form-item -->
        
        <div class="pcbuilder-budget-form-item">
            <label for="">Extra options:</label>
            <select class="pcbuilder-extra" disabled>
                <option selected disabled value="">Extra List</option>
                <?php 
                    if(!empty($extras)){
                        foreach($extras as $extra){
                            echo '<option value="'.$extra.'">'.$extra.'</option>';
                        }
                    }
                ?>
            </select>
        </div><!-- pcbuilder-budget-form-item -->

        <div class="pcbuilder-budget-form-item">
            <button class="pcbuilder-budget-addrow">Add</button>
            <button class="pcbuilder-budget-cancel">Cancel</button>
        </div><!-- pcbuilder-budget-form-item -->
    </div><!-- pcbuilder-budget-formco -->
</div><!-- pcbuilder-budget-form -->