<div class="crm-public-form-item crm-section delayedFields-section">
  {foreach from=$delayedFields key=id item=field}
    <div class="label">{$form.$field.label}</div>
    <div class="content">{$form.$field.html}&nbsp;</div>
  {/foreach}
</div>
