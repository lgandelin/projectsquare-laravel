<div class="beta-form">
    <a href="#" class="toggle" title="Nous contacter">
        <span class="glyphicon glyphicon-envelope icon"></span>
    </a>
</div>

<div class="modal fade" id="beta-form-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Contacter l'assistance technique de Projectsquare</h4>
                </div>
                <div class="modal-body">

                    <p style="font-size: 1.4rem; font-style: italic; color: #aaa">Vous avez remarqué un bug dans la plateforme ou vous souhaitez nous faire une demande d'évolution ? N'hésitez pas à contacter l'assistance technique de Projectsquare.</p>

                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::conversations.title') }}" name="title" />
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" placeholder="{{ trans('projectsquare::conversations.message') }}" name="message" rows="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn valid valid-beta-form"><i class="glyphicon glyphicon-ok"></i> Valider</button>
                    <button type="button" class="btn back" data-dismiss="modal"><span class="glyphicon glyphicon-arrow-left"></span> Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>