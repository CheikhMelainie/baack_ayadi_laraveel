<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tester la Transaction</title>
</head>
<body>
    <h1>Formulaire de Transaction</h1>

    <form action="/api/transaction/create" method="POST">
        @csrf <!-- Ajoute un jeton CSRF pour sécuriser le formulaire dans Laravel -->

        <label for="amount">Montant :</label>
        <input type="text" id="amount" name="amount" required><br>

        <label for="currency">Devise :</label>
        <input type="text" id="currency" name="currency" value="929"><br>

        <label for="description">Description :</label>
        <input type="text" id="description" name="description" value="Achat en ligne"><br>

        <label for="brand">Marque :</label>
        <input type="text" id="brand" name="brand" value="bmci"><br>

        <label for="purchase_ref">Référence d'achat :</label>
        <input type="text" id="purchase_ref" name="purchase_ref" value="784512456745"><br>

        <label for="phonenumber">Numéro de téléphone :</label>
        <input type="text" id="phonenumber" name="phonenumber"><br>

        <label for="accepturl">URL d'acceptation :</label>
        <input type="text" id="accepturl" name="accepturl" value="https://165.227.85.96/ayadi/payment/success/"><br>

        <label for="declineurl">URL de refus :</label>
        <input type="text" id="declineurl" name="declineurl" value="https://165.227.85.96/ayadi/payment/decline/"><br>

        <label for="cancelurl">URL d'annulation :</label>
        <input type="text" id="cancelurl" name="cancelurl" value="https://165.227.85.96/ayadi/payment/cancel/"><br>

        <label for="text">Message :</label>
        <input type="text" id="text" name="text" value="Thank you for your purchase"><br>

        <button type="submit">Envoyer la Transaction</button>
    </form>
</body>
</html>
