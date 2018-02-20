class EmployeeSearch
{
    constructor(inputElementSelector, outputElementSelector, isOnlySpecialist = false)
    {
        this.inputElement = $(inputElementSelector);
        this.outputElement = $(outputElementSelector);
        
        this.isOnlySpecialist = isOnlySpecialist;
        
        this.isRequesting = false;
        
        this.addInputElementListener();
    }
    
    addInputElementListener()
    {
        this.inputElement.addEventListener('input', () =>
        {
            this.outputElement.html('');
            
            if (this.inputElement.val().length === 0) return;
            
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

                    this.outputElement.html(response);

                    this.isRequesting = false;
                }
            }
        };
        
        const searchTerm = this.inputElement.val().trim().toLowerCase();
        
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