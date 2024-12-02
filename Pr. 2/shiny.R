#Load the Shiny Package
library(shiny)

#Define UI for the app
ui <- fluidPage(
  
  #Title panel
  titlePanel("Simple Shiny App"),
  
  #sidebar
  sidebarLayout(
    
    # Position sidebar on right
    sidebarPanel(
      h3("Sidebar Panel"),
      p("This is the sidebar panel, which is positioned on the right."),
      position = "right"
    ),
    
    # Main panel to display output
    mainPanel(
      h3("Main Panel"),
      p("This is the main panel displaying the main content.")
    )
  )
)

server <- function(input, output) {
  # No server-side logic required for this simple app
}

shinyApp(ui = ui, server = server)