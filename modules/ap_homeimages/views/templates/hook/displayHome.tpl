<div id="ap_homeimages">
    <div class="row">
        {if $imageList[0].position == 1}
            <div class="col-md-7 col-sm-12" style="background-image: {$imageList[0].image};">
                <a href="{$imageList[0].cta_link}">
                    {$imageList[0].cta_text}
                </a>
            </div>
        {/if}

        <div class="col-md-5 col-sm-12">
            {if $imageList[1].position == 2}
                <a href="{$imageList[1].cta_link}">
                    <img src="{$imageList[1].image}" alt="{$imageList[1].cta_text}" class="img-responsive">
                </a>
            {/if}

            {if $imageList[2].position == 3}
                <a href="{$imageList[2].cta_link}">
                    <img src="{$imageList[2].image}" alt="{$imageList[2].cta_text}" class="img-responsive">
                </a>
            {/if}
        </div>
    </div>
</div>
