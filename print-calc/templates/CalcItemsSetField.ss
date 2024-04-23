<% with $CalculationRequest %>
    <table>
        <tr>
            <th>
                $Calculations.First.OrderDeadline.i18n_singular_name
                /
                $Calculations.First.OffsetCalculationItems.First.fieldLabel("Circulation")
            </th>
            <% loop $Calculations.First.OffsetCalculationItems.Sort("Circulation ASC") %>
                <th>{$Circulation}</th>
            <% end_loop %>
        </tr>
        <% loop $getCalculationsOrderedDesc %>
            <tr>
                <td>{$OrderDeadline.Name}</td>
                <% loop $CalculationItems.Sort("Circulation ASC, Price ASC") %>
                    <% if $Odd %>
                        <td>
                            <input id="{$Top.ID}_{$ID}" class="radio" name="{$Top.Name}" value="{$ID}" type="radio" />
                            <label for="{$Top.ID}_{$ID}">{$Price.Nice}</label>
                        </td>
                    <% end_if %>
                <% end_loop %>
            </tr>
        <% end_loop %>
    </table>
<% end_with %>