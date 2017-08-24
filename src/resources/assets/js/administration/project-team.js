$(document).ready(function() {

    $('.team-project-template').on('click', '.btn-add-user', function() {
        var user = $(this).closest('.child');

        $(this).hide();

        var html = loadTemplate('user-template', {
           id: user.data('id'),
           role: user.data('role'),
           name: user.data('name')
        });

        $('.project-team table tbody').append(html);

        $('.team-project-template .middle-column .filter-user .search-input').val('');
        filter_users_list();

        update_user_ids_field();
   });

    $('.team-project-template').on('click', '.btn-delete-user', function() {
        var user_id = $(this).closest('tr').data('id');
        $(this).closest('tr').remove();

        $('.team-project-template .middle-column .child[data-id="' + user_id + '"] .btn-add-user').show();

        update_user_ids_field();
    });

    $('.team-project-template .middle-column .filter-user .search-input').on('keyup', function() {
        filter_users_list();
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

function filter_users_list() {
    var input_search = $('.team-project-template .middle-column .filter-user .search-input');

    $('.team-project-template .middle-column .child').each(function() {
        var show = false;

        if (input_search.val().length == 0 || $(this).is(':contains("' + input_search.val() + '")'))
            show = true;

        if (show)
            $(this).fadeIn();
        else
            $(this).fadeOut();
    });
}