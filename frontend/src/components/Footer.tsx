import type React from "react"
import { Box, Container, Grid, Typography, Link, IconButton, Divider, useTheme } from "@mui/material"
import {
  Facebook as FacebookIcon,
  Twitter as TwitterIcon,
  Instagram as InstagramIcon,
  Restaurant as RestaurantIcon,
} from "@mui/icons-material"

const Footer: React.FC = () => {
  const theme = useTheme()

  return (
    <Box
      component="footer"
      sx={{
        backgroundColor: theme.palette.grey[100],
        py: 6,
        [theme.breakpoints.down("sm")]: {
          py: 4,
        },
      }}
    >
      <Container maxWidth="lg">
        <Grid container spacing={4}>
          <Grid item xs={12} sm={4}>
            <Box display="flex" alignItems="center" mb={2}>
              <RestaurantIcon sx={{ mr: 1, color: "primary.main" }} />
              <Typography variant="h6" color="primary" fontWeight="bold">
                Khabo Koi
              </Typography>
            </Box>
            <Typography variant="body2" color="text.secondary" paragraph>
              Discover and review the best restaurants in your area. Share your culinary adventures with food lovers
              around the world.
            </Typography>
          </Grid>
          <Grid item xs={12} sm={4}>
            <Typography variant="h6" color="text.primary" gutterBottom>
              Quick Links
            </Typography>
            <Link href="#" color="text.secondary" display="block" mb={1}>
              Home
            </Link>
            <Link href="#" color="text.secondary" display="block" mb={1}>
              Restaurants
            </Link>
            <Link href="#" color="text.secondary" display="block" mb={1}>
              Reviews
            </Link>
            <Link href="#" color="text.secondary" display="block" mb={1}>
              About Us
            </Link>
          </Grid>
          <Grid item xs={12} sm={4}>
            <Typography variant="h6" color="text.primary" gutterBottom>
              Contact Us
            </Typography>
            <Typography variant="body2" color="text.secondary">
              Email: info@khabokoi.com
            </Typography>
            <Typography variant="body2" color="text.secondary">
              Phone: +1 (123) 456-7890
            </Typography>
            <Box mt={2}>
              <IconButton aria-label="Facebook" color="primary">
                <FacebookIcon />
              </IconButton>
              <IconButton aria-label="Twitter" color="primary">
                <TwitterIcon />
              </IconButton>
              <IconButton aria-label="Instagram" color="primary">
                <InstagramIcon />
              </IconButton>
            </Box>
          </Grid>
        </Grid>
        <Divider sx={{ my: 3 }} />
        <Typography variant="body2" color="text.secondary" align="center">
          © {new Date().getFullYear()} Khabo Koi. All rights reserved.
        </Typography>
      </Container>
    </Box>
  )
}

export default Footer

