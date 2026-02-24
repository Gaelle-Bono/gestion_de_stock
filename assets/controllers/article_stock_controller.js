import { Controller } from '@hotwired/stimulus';
export default class extends Controller {

    static targets = ['quantity'];

    connect() {
    }

    decrease(event){
        event.preventDefault();
        const link = event.currentTarget.dataset.href;
        this.#handle(link);
    }

    increase(event){
        event.preventDefault();
        const link = event.currentTarget.dataset.href;
        this.#handle(link);
    }

    #handle(link){
        fetch(link) 
            .then(response => response.json())
            .then(data=> {
                if (data.quantity) {
                    this.quantityTarget.innerText = data.quantity;
                }
            });
    }
}
