<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Demo - Delete</title>
</head>
<body>
    <h1>Delete</h1>
    <a href="/demo">Back to List</a><br><br>

    <form action="/demo/delete" method="post">
        <label>Key (Target):</label>
        <input type="text" name="key" required>
        <br><br>
        <button type="submit">Delete</button>
    </form>
</body>
</html>
