<div class="crm-public-form-item crm-section delayedFields-section">
  {foreach from=$delayedFields key=id item=field}
    <div class="label">{$form.$field.label}</div>
    <div class="content">{$form.$field.html}</div>
    <div class="clear"></div>
  {/foreach}
</div>

{literal}
    <script type="text/javascript">
        CRM.$(function($) {
            $('.delayedFields-section').insertBefore($('#payment_information'));
            $('.delayedFields-section').appendTo($('.amount_display-group'));
        });
    </script>
{/literal}
