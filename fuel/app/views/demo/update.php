<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Demo - Update</title>
</head>
<body>
    <h1>Update</h1>
    <a href="/demo">Back to List</a><br><br>

    <form action="/demo/update" method="post">
        <label>Key (Target):</label>
        <input type="text" name="key" required>
        <br>
        <label>Value (New Value):</label>
        <input type="text" name="value" required>
        <br><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
