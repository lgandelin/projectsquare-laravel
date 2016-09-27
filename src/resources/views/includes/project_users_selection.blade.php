<script>
    $(document).ready(function() {
        $('select[name="@if (isset($select_project_name)){{ $select_project_name}}@else{{ 'project_id' }}@endif"]').change(function() {

            var select_users = $('select[name="@if (isset($select_user_name)){{ $select_user_name}}@else{{ 'allocated_user_id' }}@endif"]');

            select_users.attr('disabled', 'disabled');
            var saved_value = select_users.val();

            var data = {
                project_id: $(this).val(),
                _token: $('#csrf_token').val()
            };

            $.ajax({
                type: "GET",
                url: route_project_users,
                data: data,
                success: function(data) {

                    //Empty current options
                    select_users.find('option').each(function() {
                        if ($(this).val() != "") {
                            $(this).remove();
                        }
                    });

                    //Re-display new options
                    for (var i = 0; i < data.length; i++) {
                        var user = data[i];
                        var selected = '';
                        if (user.id == saved_value) {
                            selected = 'selected="selected"';
                        }
                        select_users.append('<option value="' + user.id + '" + ' + selected + '>' + user.first_name + ' ' + user.last_name + '</option>');
                    }

                    select_users.removeAttr('disabled');
                }
            });
        });
    });
</script>