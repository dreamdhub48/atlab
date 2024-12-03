library(shiny)
  
  ui <- fluidPage(
    tags$head(
      tags$style(HTML("
        .title-container {
          text-align: center;
          margin-bottom: 20px;
        }
        .result-container {
          text-align: center;
          margin-top: 20px;
        }
      "))
    ),
    
    div(class = "title-container",
        titlePanel("Square and Square Root Calculator")
    ),
    
    sidebarLayout(
      sidebarPanel(
        h3("Calculate"),
        numericInput("number", "Enter a number:", 
                    value = 0, min = 0),
        actionButton("calculate_square", "Calculate Square"),
        
        actionButton("calculate_sqrt", "Calculate Square Root")
      ),
      
      mainPanel(
        div(class = "result-container",
            h3("Result"),
            textOutput("square_result"),
            tags$br(),  # Adds a blank line
            textOutput("sqrt_result")
        )
      )
    )
  )
  
  server <- function(input, output) {
    output$square_result <- renderText({
      req(input$calculate_square)
      num <- input$number
      paste(num, "squared is", num^2)
    })
    
    output$sqrt_result <- renderText({
      req(input$calculate_sqrt)
      num <- input$number
      paste("Square root of", num, "is", round(sqrt(num), 2))
    })
  }

shinyApp(ui, server)