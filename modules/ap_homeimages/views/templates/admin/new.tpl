<form action="{$controllerUrl}" method="post" enctype="multipart/form-data" class="container">
    <div class="panel">
        <h3 class="panel-title"><i class="fa fa-cogs"></i> {l s='New Home Image'}</h3>
        <div class="panel-body">
            <input type="hidden" value="save" name="action">
            <input type="hidden" value="{$image.id|default:0}" name="data[id]">
            <div class="form-group">
                {if !empty($image.image)}
                    <img src="{$image.image}" alt="{$image.cta_text}" style="max-width: 100px;">
                {/if}
                <label for="image">{l s='Image'}</label>
                <input type="file" class="form-control" id="image" name="data[image]" value="{$image.image|default:''}" {if empty($image.image)}required{/if}>
            </div>
            <div class="form-group">
                <label for="cta_link">{l s='CTA Link'}</label>
                <input type="text" class="form-control" id="cta_link" name="data[cta_link]" value="{$image.cta_link|default:''}"required>
            </div>
            <div class="form-group">
                <label for="cta_text">{l s='CTA Text'}</label>
                <input type="text" class="form-control" id="cta_text" name="data[cta_text]" value="{$image.cta_text|default:''}" required>
            </div>
            <div class="form-group">
                <label for="position">{l s='Position'}</label>
                <input type="number" class="form-control" id="position" name="data[position]" min="1" max="3" value="{$image.position|default:$nextPosition}" required>
            </div>
        </div>
        <div class="panel-footer">
            <a href="{$controllerUrl}" class="btn btn-default">
                {l s='Cancel'}
            </a>
            <button type="submit" class="btn btn-primary">
                {l s='Save'}
            </button>
        </div>
    </div>
</form>
