import type React from "react"
import { useState } from "react"
import { TextField, Button, Box, Typography, Paper, InputAdornment, Divider } from "@mui/material"
import { Person, Email, Lock, Restaurant } from "@mui/icons-material"

interface FormData {
  username: string
  email: string
  password: string
  conf_pass: string
}

const SignupForm: React.FC = () => {
  const [formData, setFormData] = useState<FormData>({
    username: "",
    email: "",
    password: "",
    conf_pass: "",
  })

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target
    setFormData((prevData) => ({ ...prevData, [name]: value }))
  }

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    if (formData.password !== formData.conf_pass) {
      alert("Passwords do not match!")
      return
    }
    alert("Signup successful!")
    // Add your signup logic here (e.g., API call)
  }

  const handleGoogleSignIn = () => {
    // Implement Google Sign-In logic here
    console.log("Google Sign-In clicked")
  }

  return (
    <Box
      sx={{
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        minHeight: "100vh",
        // backgroundColor: "#FFF3E0", // Light orange background
      }}
    >
      <Paper
        elevation={3}
        sx={{
          width: 400,
          padding: 4,
          borderRadius: 4,
          boxShadow: "0 4px 20px rgba(0,0,0,0.1)",
        }}
      >
        <Box sx={{ textAlign: "center", mb: 3 }}>
          <Typography variant="h4" gutterBottom sx={{ color: "#FF5722", fontWeight: "bold" }}>
            Khabo Koi
          </Typography>
          <Typography variant="subtitle1" sx={{ color: "#757575" }}>
            Join the culinary community!
          </Typography>
        </Box>

        <form onSubmit={handleSubmit}>
          <TextField
            fullWidth
            label="Username"
            variant="outlined"
            margin="normal"
            name="username"
            value={formData.username}
            onChange={handleInputChange}
            required
            InputProps={{
              startAdornment: (
                <InputAdornment position="start">
                  <Person color="action" />
                </InputAdornment>
              ),
            }}
          />
          <TextField
            fullWidth
            label="Email"
            variant="outlined"
            margin="normal"
            type="email"
            name="email"
            value={formData.email}
            onChange={handleInputChange}
            required
            InputProps={{
              startAdornment: (
                <InputAdornment position="start">
                  <Email color="action" />
                </InputAdornment>
              ),
            }}
          />
          <TextField
            fullWidth
            label="Password"
            variant="outlined"
            margin="normal"
            type="password"
            name="password"
            value={formData.password}
            onChange={handleInputChange}
            required
            InputProps={{
              startAdornment: (
                <InputAdornment position="start">
                  <Lock color="action" />
                </InputAdornment>
              ),
            }}
          />
          <TextField
            fullWidth
            label="Confirm Password"
            variant="outlined"
            margin="normal"
            type="password"
            name="conf_pass"
            value={formData.conf_pass}
            onChange={handleInputChange}
            required
            InputProps={{
              startAdornment: (
                <InputAdornment position="start">
                  <Lock color="action" />
                </InputAdornment>
              ),
            }}
          />
          <Button
            type="submit"
            fullWidth
            variant="contained"
            sx={{
              mt: 3,
              mb: 2,
              backgroundColor: "#FF5722",
              "&:hover": { backgroundColor: "#E64A19" },
              borderRadius: "20px",
              padding: "12px",
            }}
          >
            Sign Up
          </Button>
        </form>

        <Divider sx={{ my: 2 }}>OR</Divider>

        <Button
          fullWidth
          variant="outlined"
          startIcon={<Restaurant />}
          onClick={handleGoogleSignIn}
          sx={{
            mt: 1,
            borderColor: "#FF5722",
            color: "#FF5722",
            "&:hover": { borderColor: "#E64A19", backgroundColor: "#FFF3E0" },
            borderRadius: "20px",
            padding: "12px",
          }}
        >
          Sign up with Google
        </Button>
      </Paper>
    </Box>
  )
}

export default SignupForm

