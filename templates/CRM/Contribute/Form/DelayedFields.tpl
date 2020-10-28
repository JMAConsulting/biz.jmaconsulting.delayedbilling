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
      $(document).ready(function() {
        var checkPayment = '{/literal}{$checkPayment}{literal}';
        $('.delayedFields-section').insertAfter($('.payment_options-group'));
        
        var initialPayment = $('input[name="payment_processor_id"]:checked').val();
        hideShowSplit(checkPayment, initialPayment);

        $('input[name="payment_processor_id"]').change(function () {
          hideShowSplit(checkPayment, $(this).val());
        });

        function hideShowSplit(check, value) {
          if (check != 'NA') {
            if (value == check) {
              $('.delayedFields-section').hide();
              $('.delayedFields-section').find('input').val('');
              $('.delayedFields-section').find('input:checkbox').prop('checked', false);
            }
            else {
              $('.delayedFields-section').show();
            }
          }  
        }
      });
    });
  </script>
{/literal}
