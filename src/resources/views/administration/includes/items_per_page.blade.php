<form id="items_per_page_form">
    <select class="items_per_page" name="it" onchange="submitForm()">
        <option value="10" @if($items_per_page == 10)selected="selected"@endif>10</option>
        <option value="25" @if($items_per_page == 25)selected="selected"@endif>25</option>
        <option value="50" @if($items_per_page == 50)selected="selected"@endif>50</option>
        <option value="100" @if($items_per_page == 100)selected="selected"@endif>100</option>
    </select>
</form>

<script type="text/javascript">
    function submitForm() {
        $('#items_per_page_form').submit();
    }
</script>