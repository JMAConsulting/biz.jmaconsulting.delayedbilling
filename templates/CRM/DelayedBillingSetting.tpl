<table style="display:none;" class="form-layout-compressed">
<tr class="delayedBilling-section">
    <th scope="row" class="label" width="20%">{$form.is_delayed.label}</th>
    <td>{$form.is_delayed.html}&nbsp;</td>
</tr>
</table>

{literal}
    <script type="text/javascript">
        CRM.$(function($) {
            $('.delayedBilling-section').insertAfter($('.crm-contribution-contributionpage-amount-form-block-payment_processor'));
        });
    </script>
{/literal}