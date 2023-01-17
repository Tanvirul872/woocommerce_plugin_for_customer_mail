<?php
wp_head();
?>

    <!-- Search Element -->
    <div id='div_search'><input type='text' id='search' placeholder="Enter search text" /></div>

    <!-- Table -->
    <table id='empTable' border='1'>
        <thead>
        <tr>
            <th>S.no</th>
            <th>Employee Name</th>
            <th>Email</th>
            <th>Salary</th>
            <th>Gender</th>
            <th>City</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>

<?php
wp_footer();