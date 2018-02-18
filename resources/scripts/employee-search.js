class EmployeeSearch
{
    constructor(inputElementSelector, outputElementSelector, isOnlySpecialist = false)
    {
        this.inputElement = document.querySelector(inputElementSelector);
        this.outputElement = document.querySelector(outputElementSelector);
        
        this.isOnlySpecialist = isOnlySpecialist;
        
        this.isRequesting = false;
        
        this.addInputElementListener();
    }
    
    addInputElementListener()
    {
        this.inputElement.addEventListener('input', () =>
        {
            this.outputElement.innerHTML = '';
            
            if (this.inputElement.value.length === 0) return;
            
            if (this.isRequesting) return;
            
            this.requestEmployees();
        });
    }
    
    requestEmployees()
    {
        this.isRequesting = true;
        
        const http = new XMLHttpRequest() || new ActiveXObject('Microsoft.XMLHTTP');
        
        http.onreadystatechange = () =>
        {
            if (http.readyState === XMLHttpRequest.DONE)
            {
                if (http.status === 200)
                {
                    const response = http.responseText;

                    this.outputElement.innerHTML = response;

                    this.isRequesting = false;
                }
            }
        };
        
        const searchTerm = this.inputElement.value.trim().toLowerCase();
        
        const parameters =
        [
            ['name', searchTerm],
            ['specialist', this.isOnlySpecialist.toString()],
        ];
        
        const parametersFormatted = parameters.map((p) => p.join('='));
        
        http.open('GET', `/resources/tickets/find-employees.php?${parametersFormatted.join('&')}`, true);
        
        http.send();
    }
}