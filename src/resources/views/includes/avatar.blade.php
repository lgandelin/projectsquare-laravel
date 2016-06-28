<?php $avatar = 'uploads/users/' . $id . '/avatar.jpg' ?>
@if (!file_exists('uploads/users/' . $id . '/avatar.jpg'))
    <?php $avatar = 'img/avatar.jpg' ?>
@endif

<img class="avatar" src="{{ asset($avatar) }}" title="{{ $name }}" alt="{{ $name }}" />