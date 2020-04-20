# Padrão de Tema WordPress

O comando Ctrl + B, no Visual Studio Code, inicia o gulp.
Para realizar o build (para deploy), digite no terminal: ./build.sh.

## Customizer

O *customizer* cumpre a função de criar campos customizáveis para o usuário, como: títulos, subtítulos, descrições, nomes, etc. Em outras palavras, servirá para disponibilizar quaisquer informações que o usuário consiga customizar conforme sua preferência.

### Como utilizar

1. Criação da seção:

    É preciso, inicialmente, definir o que é uma seção. Neste contexto, uma seção define-se por **um grupo de campos de uma página ou componente em específico**.

    Crie, então, um arquivo chamado *customizer-$nome-da-sua-seção.php* no caminho *includes/customizer*. Criaremos, para exemplificar, uma seção de nome "home".

    ```files
    project
    |   README.md
    |
    └───includes
    |   └───customizer
    |   |   |   customizer-home.php
    |   |   ...
    |   ...
    ...
    ```

    Neste novo arquivo crie uma função que irá criar definir a seção "home", conforme o exemplo a seguir:

    ```php
    require_once(get_template_directory() . '/includes/customizer/utils.php'); ///utils do customizer.

    function customizer_home{
        $section = 'home';
        customizer_section($wp_customize, $section, 'Título da Seção Home', "Descrição da seção Home.");
    }
    ```

    Agora, dentro do arquivo *includes/customizer/customizer.php* adiciona-se a chamada para a função que irá criar os campos de uma página ou componente em específico, nomearemos um grupo de campos como **seção**.

    ```php
    require get_template_directory() . '/includes/customizer/customizer-home.php';

    function customizer_theme($wp_customize){
        customizer_home($wp_customize);
    }
    ...
    ```
