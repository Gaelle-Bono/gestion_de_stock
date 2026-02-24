import { Controller } from '@hotwired/stimulus';
export default class extends Controller {
    connect() {
        this.span = this.element.querySelector('span');
    }

    decrease(event){
        event.preventDefault();

        const link = event.currentTarget.dataset.href;
        
        fetch(link) 
            .then(response => response.json())
            .then(data=> {
                if (data.quantity) {
                    this.span.innerText = data.quantity;
                }
            });
    }

    increase(event){
        event.preventDefault();

        const link = event.currentTarget.dataset.href;
        
        fetch(link) 
            .then(response => response.json())
            .then(data=> {
                if (data.quantity) {
                    this.span.innerText = data.quantity;
                }
            });   
    }

}
