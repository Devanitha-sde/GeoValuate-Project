import pypdf

def extract_text_from_pdf(pdf_path):
    with open(pdf_path, 'rb') as file:
        reader = pypdf.PdfReader(file)
        text = ""
        for page in reader.pages:
            text += page.extract_text()
        return text

if __name__ == "__main__":
    pdf_path = "Features of the System.pdf"
    text = extract_text_from_pdf(pdf_path)
    with open("features_clean.txt", "w", encoding="utf-8") as f:
        f.write(text)
