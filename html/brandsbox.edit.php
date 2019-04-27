<tr class="form-field term-parent-wrap">
<th scope="row"><label for="parent">GPU Manufacturer:</label></th>
<td>
<select name="component_gpu">
<option <?= (@$component_gpu == 0 ? 'selected' : '') ?>  value="0">No</option>
<option <?= (@$component_gpu == 1 ? 'selected' : '') ?>  value="1">Yes</option>
</select>
</td>
</tr>

<tr class="form-field term-parent-wrap">
<th scope="row"><label for="parent">CPU Manufacturer:</label></th>
<td>
<select name="component_cpu">
<option <?= (@$component_cpu == 0 ? 'selected' : '') ?>  value="0">No</option>
<option <?= (@$component_cpu == 1 ? 'selected' : '') ?>  value="1">Yes</option>
</select>
</td>
</tr>