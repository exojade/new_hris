import qrcode
import base64

def convert_image_to_qrcode(image_path, output_path):
    # Read the image data
    with open(image_path, "rb") as image_file:
        image_data = image_file.read()

    # Encode image data as Base64
    image_base64 = base64.b64encode(image_data).decode('utf-8')

    # Choose an appropriate version based on the size of your data
    # You can experiment with different values to find the optimal version
    version = 5

    # Create a QRCode instance
    qr = qrcode.QRCode(
        version=version,
        error_correction=qrcode.constants.ERROR_CORRECT_L,
        box_size=10,
        border=4,
    )

    # Add Base64-encoded data to the QRCode
    qr.add_data(image_base64)
    qr.make(fit=True)

    # Create an image from the QRCode instance
    img = qr.make_image(fill_color="black", back_color="white")

    # Save the generated QR code image
    img.save(output_path)

# Example usage
convert_image_to_qrcode("sample_image.jpg", "output_qrcode.png")
