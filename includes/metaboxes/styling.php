<style scoped>
  .metabox__row-2-10{
    display: grid;
    grid-template-columns: 2fr 10fr;
    align-items: flex-start;
    grid-column-gap: 20px;
  }

  .metabox__row-2-8-2 {
    display: grid;
    grid-template-columns: 2fr 8fr 2fr;
    align-items: flex-start;
    grid-column-gap: 15px;
  }

  .metabox__row-2-10 {
    display: grid;
    grid-template-columns: 2fr 10fr;
    align-items: flex-start;
    column-gap: 15px;
  }

  .metaboxes {
    display: grid;
    grid-template-columns: 1fr;
    row-gap: 15px;
  }

  .adicionar {
    padding: 8px 0;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;

    color: #0071a1;
    background: #f3f5f6;
    border: 1px solid #0071a1;
  }
  
  .metabox label {
    font-size: 15px;
    font-style: italic;
  }

  .metabox__title {
    font-size: 24px;
    margin-bottom: 0px;
    color: #003366;
  }

  .metabox__added {
    grid-column: 2;
    display: grid;
    grid-template-columns: 1fr;
    row-gap: 5px;
  }
  .metabox__added__item {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background: #F5F5F5;
  }
  .metabox__added button {
    padding: 8px;
    border: 1px solid #dc3232;
    background: #F1F1F1;
    color: #dc3232;
    border-radius: 4px;
    cursor: pointer;
  }
  .metabox__added button:hover {
    background: #dc3232;
    color: white;
  }

  .metabox__flex-column {
    display: flex;
    flex-direction: column;
  }

  .metabox__flex-row {
    display: flex;
    flex-direction: row;
  }

</style>