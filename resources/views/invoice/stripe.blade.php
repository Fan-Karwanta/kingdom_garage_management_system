<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
</head>
<body>
    <h1>Stripe Payment Page</h1>
    <form action="{{ route('stripe.checkout') }}" method="GET">
        <label for="amount">Amount</label>
        <input type="number" name="amount" id="amount" placeholder="Enter amount" required>
        <button type="submit">Pay with Stripe</button>
    </form>
</body>
</html>
