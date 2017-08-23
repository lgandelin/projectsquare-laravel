$(document).ready(function() {

   $('.team-project-template .middle-column .child .add-user').click(function() {
       var user = $(this).closest('.child');

       $(this).hide();

       var html = loadTemplate('user-template', {
           id: user.data('id'),
           role: user.data('role'),
           name: user.data('name')
       });

       $('.project-team table tbody').append(html);

       update_user_ids_field();
   });

    $('.team-project-template').on('click', '.btn-delete', function() {
        var user_id = $(this).closest('tr').data('id');
        $(this).closest('tr').remove();

        $('.team-project-template .middle-column .child[data-id="' + user_id + '"] .add-user').show();

        update_user_ids_field();
    });
});

function update_user_ids_field() {
    var users = [];
    $('.team-project-template table tr').each(function() {
        var user_id = $(this).data('id');
        if (user_id) users.push(user_id);
        $('#user_ids').val(JSON.stringify(users));
    });
}