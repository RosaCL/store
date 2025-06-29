document.querySelectorAll('input[type="number"]').forEach(inputNumber =>{
    inputNumber.oninput = () =>{
        if(inputNumber.value.length > inputNumber.maxlength) inputNumber.value = inputNumber.value.slice(0, inputNumber.maxlength);
    };
});