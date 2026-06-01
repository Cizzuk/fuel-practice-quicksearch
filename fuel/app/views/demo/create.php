<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Demo - Create</title>
</head>
<body>
    <h1>Create</h1>
    <a href="/demo">Back to List</a><br><br>

    <form action="/demo/create" method="post">
        <label>Key:</label>
        <input type="text" name="key" required>
        <br>
        <label>Value:</label>
        <input type="text" name="value" required>
        <br><br>
        <button type="submit">Add</button>
    </form>
</body>
</html>
