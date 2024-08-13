class GestionStripe extends User {

    // You need to add this : <script src="https://js.stripe.com/v3/" data-js-isolation="on"></script>
    // to the page you want to use strip

    stripe = null

    async startStripeUseThisOne(amount, name) {
        startLoading()

        try{
            this.stripe = Stripe('pk_test_51PmzntFP4zc2O5WMI4vIV6PJuCwgI98zC5bvSjUoBFkBjQ32vONiDvEw4wWezAbeNYiIVI4MuF52OiI5v7xzmTBf00KmHRrRPt');
        }
        catch{
            console.log('PLS ADD THIS TO THE PAGE : <script src=\"https://js.stripe.com/v3/\" data-js-isolation=\"on\"></script>')
            popup("If you're a dev, plz check the console")
            stopLoading()
            return
        }

        try {
            await this.startPaymentDoNotUse(amount, name);
        } catch (error) {
            console.log('Erreur lors du démarrage du paiement:', error);
        } finally {
            stopLoading();
        }

    }

    /**
     * Private function if the private keyword exist....
     * @param amount
     * @param name
     * @returns {Promise<void>}
     */
    async startPaymentDoNotUse(amount, name) {

        const data = {
            "amount": amount,
            "name": name
        };

        try {
            const response = await fetch(this.adresse + '/stripe/payment/', this.optionPost(data));
            if (!response.ok) {
                console.log(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.sessionId) {
                // Stripe Checkout pour afficher le formulaire de paiement
                const {error} = await this.stripe.redirectToCheckout({
                    sessionId: result.sessionId
                });

                if (error) {
                    console.error('Erreur lors de la redirection vers Checkout:', error);
                }
            } else if (result.error) {
                console.error('Erreur:', result.error);
            }
        } catch (error) {
            console.log('Erreur lors de la requête:', error);
        }
    }

}