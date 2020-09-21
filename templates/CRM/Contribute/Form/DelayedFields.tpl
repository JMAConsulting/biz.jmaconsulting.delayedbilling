<div class="crm-public-form-item crm-section delayedFields-section">
  <fieldset>
    <legend>Split Payment</legend>
    {foreach from=$delayedFields key=id item=field}
      <div class="crm-section">
        {if $field eq 'partial_payment'}
          <div class="label">{$form.$field.html}{$form.$field.label}</div>
        {else}
          <div class="label">{$form.$field.label}</div>
          <div class="content">{$form.$field.html}</div>
          <div class="clear"></div>
        {/if}
      </div>
    {/foreach}
  </fieldset>
</div>

{literal}
  <script type="text/javascript">
    CRM.$(function($) {
      $('.delayedFields-section').insertAfter($('#membership').parent());
      $('.delayedFields-section').appendTo($('.amount_display-group'));
    });
  </script>
{/literal}
