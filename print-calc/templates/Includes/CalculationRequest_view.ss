<% if $FieldsForView %>
    <table>
        <% loop $FieldsForView %>
            <tr>
                <td>{$Name}</td>
                <td>{$Value}</td></tr>
        <% end_loop %>
    </table>
<% end_if %>