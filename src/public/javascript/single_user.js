function validate(event, first, last){
    event.preventDefault();
    confirm(`Are you sure you want to delete ${first} ${last}?`);
}
