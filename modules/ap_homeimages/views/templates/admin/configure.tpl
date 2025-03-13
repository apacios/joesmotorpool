<div class="container">
    <div class="panel">
        <h3 class="panel-title"><i class="fa fa-cogs"></i> {l s='Configure Home Images' d='Modules.ApHomeimages.configure'}</h3>
        <div class="panel-body">
            {if empty($imageList)}
                <div class="alert alert-info">
                    {l s='You have not added any images yet.' d='Modules.ApHomeimages.configure'}
                </div>

                <form action="{$controllerUrl}" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="new" name="action">
                    <button type="submit" class="btn btn-primary" style="text-transform: capitalize;">
                        {l s='Add New Image' d='Modules.ApHomeimages.configure'}
                    </button>
                </form>
            {else}
                <table class="table">
                    <thead>
                        <tr>
                            <th><strong>{l s='Position' d='Modules.ApHomeimages.configure'}</strong></th>
                            <th><strong>{l s='Image' d='Modules.ApHomeimages.configure'}</strong></th>
                            <th><strong>{l s='CTA Link' d='Modules.ApHomeimages.configure'}</strong></th>
                            <th><strong>{l s='CTA Text' d='Modules.ApHomeimages.configure'}</strong></th>
                            <th class="text-right"><strong>{l s='Actions' d='Modules.ApHomeimages.configure'}</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$imageList item=image}
                            <tr>
                                <td><strong>{$image.position}</strong></td>
                                <td>
                                    <img src="{$image.image}" alt="{$image.cta_text}" style="max-width: 100px;">
                                </td>
                                <td>{$image.cta_link}</td>
                                <td>{$image.cta_text}</td>
                                <td class="text-right">
                                    <form action="{$controllerUrl}" method="post" style="display: inline-block;">
                                        <input type="hidden" value="edit" name="action">
                                        <input type="hidden" value="{$image.id}" name="id">
                                        <button type="submit" class="btn btn-primary" style="text-transform: capitalize;">
                                            {l s='Edit' d='Modules.ApHomeimages.configure'}
                                        </button>
                                    </form>
                                    <form action="{$controllerUrl}" method="post" style="display: inline-block;">
                                        <input type="hidden" value="remove" name="action">
                                        <input type="hidden" value="{$image.id}" name="id">
                                        <button type="submit" class="btn btn-danger">
                                            {l s='Delete' d='Modules.ApHomeimages.configure'}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            {/if}
        </div>
        <div class="panel-footer">
            {if count($imageList) < 3 && !empty($imageList)}
                <form action="{$controllerUrl}" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="new" name="action">
                    <button type="submit" class="btn btn-primary" style="text-transform: capitalize;">
                        {l s='Add New Image' d='Modules.ApHomeimages.configure'}
                    </button>
                </form>
            {/if}
        </div>
    </div>
</div>
