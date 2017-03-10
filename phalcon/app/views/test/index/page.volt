{% extends "test/master.volt" %}
{% block content %}
    <table>
        <tr>
            <th>Id</th>
            <th>Name</th>
        </tr>
        <?php foreach ($page->items as $item) { ?>
        <tr>
            <td><?php echo $item->id; ?></td>
            <td><?php echo $item->name; ?></td>
        </tr>
        <?php } ?>
    </table>
{% endblock %}
{% block js %}
    <script>
        var json = {
            test: "aaaa"
        };
        $.post('/aaa', json, function (jsonData) {
            if (jsonData.status == 1) {

            } else {
                alert(jsonData.message);
            }
        }, "json")
    </script>
{% endblock %}